import RPi.GPIO as GPIO
import time
from flask import Flask, request
from flask_cors import CORS,cross_origin
from multiprocessing import Process, Value
import requests

#variables
led = 17 #pin 11, fan
fan = 18 #pin 12, fan
servo = 2 #pin 3, servo motor
motor_pin = 27 #pin 13 ,sulama motoru

#setup
#Raspberry Pi'in pin numarası değil,
#GPIO numarası kullanılacak
GPIO.setmode(GPIO.BCM)

#Cihaz pinleri çıkış olarak ayarla
GPIO.setup(led,GPIO.OUT)
GPIO.setup(fan,GPIO.OUT)
GPIO.setup(servo,GPIO.OUT)
GPIO.setup(motor_pin, GPIO.OUT)

#GPIO Uyarıları ekrana gösterilmesin
GPIO.setwarnings(False)

#sulama motoru kapalı durumda olsun
GPIO.output(motor_pin,False)

#led'in farklı seviyelerde yanması için pwm üretilecek
led_pwm = GPIO.PWM(led,500)
led_pwm.start(0) # ilk başta kapalı

#fan için de aynı şekilde
fan_pwm = GPIO.PWM(fan,2000)
fan_pwm.start(0)

#servo motorun dönmesi için pwm sinyali üretilecek
#50 Hz pwm üretilecek
# bu 20 ms periyod demek, servo motor standartı
servo_pwm = GPIO.PWM(servo,50)
servo_pwm.start(0) #sabit durumda
GPIO.output(servo,False) #kapalı

#websiteden gelen verileri, yani komutları alabilmek için
#flask sunucusu kullanılacak
app = Flask(_name_)
#cors farklı sunucular arasında
#http request'e izin vermek için kullanılır
cors = CORS(app)
app.config['CORS_HEADERS'] = 'Content-Type'

#ana sayfa path
#test amaçlıdır
@app.route('/')
def hello():
    return 'Welcome to Smart Plant Care System!'

#led yakma ve söndürme path
@app.route('/api/led')
def ledFunc():
    action = request.args.get('action')
    brightness = int(request.args.get('led_brightness'))
    led_status = int(request.args.get('led_status'))
    print(led_status)
    if (action == "on" and led_status == 1):
        led_pwm.ChangeDutyCycle(brightness)
        print("LED ON")
        return "OK"
    else:
        led_pwm.ChangeDutyCycle(0)
        print("LED OFF")
        return "OK"
    
# fan aç kapa
@app.route('/api/fan')
def fanFunc():
    action = request.args.get('action')
    fan_speed = int(request.args.get('fan_speed'))
    fan_status = int(request.args.get('fan_status'))
    if (action == "on" and fan_status == 1):
        fan_pwm.ChangeDutyCycle(fan_speed)
        print(f"Fan on {fan_speed}")
        return "OK"
    else:
        fan_pwm.ChangeDutyCycle(0)
        print("Fan off")
        return "OK"
    return "OK"

#motor aç kapa
@app.route('/api/motor')
def motor():
    action = request.args.get('action')
    if (action == "on"):
        GPIO.output(motor_pin,True)
        print("Motor on")
        return "OK"
    else:
        GPIO.output(motor_pin,False)
        print("Motor off")
        return "OK"
    return "OK"

#servo motor aç kapa
@app.route('/api/servo')
def servoFunc():
    action = request.args.get('action')
    if (action == "on"):
        turnServo(180)
        return "OK"
    else:
        turnServo(0)
        return "OK"
    return "OK"

#servo motor döndürme fonksiyonu
def turnServo(degree):
    GPIO.output(servo,True)
    if(degree == 180):
        for i in range(2, 12, 1):
            j = i + 0.5
            servo_pwm.ChangeDutyCycle(j)
            time.sleep(0.05)
    elif(degree == 0):
        for i in range(11, 1, -1):
            j = i + 0.5
            servo_pwm.ChangeDutyCycle(j)
            time.sleep(0.05)
    servo_pwm.ChangeDutyCycle(0)
    GPIO.output(servo,False)
    
#sunucu port 8080'de çalışacak
if _name_ == '_main_':
    app.run(host='0.0.0.0', port= 8080)