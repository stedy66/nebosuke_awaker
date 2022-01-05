import RPi.GPIO as GPIO
import time
import os

PIR_PIN = 24
LED_PIN = 17

s = 2
a = "洗面台へ移動"


GPIO.setmode(GPIO.BCM)
GPIO.setup(PIR_PIN, GPIO.IN, pull_up_down=GPIO.PUD_OFF)
GPIO.setup(LED_PIN, GPIO.OUT)

def readData():
    return str(s) + "," + a + "," + str(p)

# 時間計測開始
time_sta = time.perf_counter()

#Motion_sensorまでループ
while GPIO.input(PIR_PIN) == GPIO.LOW:
    time.sleep(0.1)
    
os.system('./AquesTalkPi ' + '洗面台へ移動を確認しました。' + '| aplay')
os.system('./AquesTalkPi ' + '顔を洗いましょう。' + '| aplay')
# 時間計測終了
time_end = time.perf_counter()
# 経過時間（秒）
p = time_end- time_sta
print(p)

readData()

GPIO.output(LED_PIN, GPIO.HIGH)
time.sleep(3)
GPIO.output(LED_PIN, GPIO.LOW)

GPIO.cleanup()