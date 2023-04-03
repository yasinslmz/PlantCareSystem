import RPi.GPIO as GPIO
import time
import requests
import sys

GPIO.setmode(GPIO.BCM)

yagmur = 3 #pin 5
lm35 = 0 # channel 0 of adc chip (MCP3008)
ldr = 1 # ch1 of adc chip
nem = 2 # ch2 of adc chip

# change these as desired - they're the pins connected from the
# SPI port on the ADC to the Cobbler
SPICLK = 11
SPIMISO = 9
SPIMOSI = 10
SPICS = 8

GPIO.setwarnings(False)
GPIO.setup(yagmur,GPIO.IN,pull_up_down=GPIO.PUD_DOWN)


# set up the SPI interface pins
GPIO.setup(SPIMOSI, GPIO.OUT)
GPIO.setup(SPIMISO, GPIO.IN)
GPIO.setup(SPICLK, GPIO.OUT)
GPIO.setup(SPICS, GPIO.OUT)

def sendToServer(payload):
    headers = {'content-type': 'application/x-www-form-urlencoded','accept':'/','user-agent': 'my-app/0.0.1'}
    r = requests.post("http://www.smartplantcare.com/rpi_post.php", data=payload, headers=headers)
    return r.status_code

def init():
    print("Initializing sensors. please wait...")
    time.sleep(5)

#read SPI data from MCP3008(or MCP3204) chip,8 possible adc's (0 thru 7)
def readadc(adcnum, clockpin, mosipin, misopin, cspin):
        if ((adcnum > 7) or (adcnum < 0)):
                return -1
        GPIO.output(cspin, True)	

        GPIO.output(clockpin, False)  # start clock low
        GPIO.output(cspin, False)     # bring CS low

        commandout = adcnum
        commandout |= 0x18  # start bit + single-ended bit
        commandout <<= 3    # we only need to send 5 bits here
        for i in range(5):
                if (commandout & 0x80):
                        GPIO.output(mosipin, True)
                else:
                        GPIO.output(mosipin, False)
                commandout <<= 1
                GPIO.output(clockpin, True)
                GPIO.output(clockpin, False)

        adcout = 0
        # read in one empty bit, one null bit and 10 ADC bits
        for i in range(12):
                GPIO.output(clockpin, True)
                GPIO.output(clockpin, False)
                adcout <<= 1
                if (GPIO.input(misopin)):
                        adcout |= 0x1

        GPIO.output(cspin, True)
        
        adcout >>= 1       # first bit is 'null' so drop it
        return adcout
    
#sıcaklık değeri oku
def readTemp():
    COlevel=readadc(lm35, SPICLK, SPIMOSI, SPIMISO, SPICS)
    value=(COlevel/1023)*5000;
    Temp=value/10
    Temp = float("{:.2f}".format(Temp))
    print(f"Temp : {Temp} C")
    return Temp

#yağmur sensörü oku
def readYagmur():
    if not GPIO.input(yagmur):
        time.sleep(0.2)
        print("Yağmur var")
        return 1
    else:
        return 0

#nem değeri oku
def readNem():
    COlevel=readadc(nem, SPICLK, SPIMOSI, SPIMISO, SPICS)
    nem_value = 100 - (COlevel/1023)*100
    nem_value = float("{:.2f}".format(nem_value))
    print(f"Nem : {nem_value} %")
    return nem_value

#ışık değeri oku
def readLDR():
    COlevel=readadc(ldr, SPICLK, SPIMOSI, SPIMISO, SPICS)
    isik = COlevel
    print("LDR : "+str("%.2f"%isik)+"")
    return isik

init()

try: 
   while True:
       temp = readTemp()
       rain = readYagmur()
       hum = readNem()
       light = readLDR()
       data = {"temp":temp,"rain":rain,"hum":hum,"light":light}
       print("  ")
       httpcode = sendToServer(data)
       print(httpcode)
       time.sleep(3)

except KeyboardInterrupt:
    GPIO.cleanup()