import RPi.GPIO as GPIO
import subprocess
import time
import os

stopbtn = 25
s = 3
a = "洗顔"


GPIO.setmode(GPIO.BCM)
GPIO.setup(stopbtn, GPIO.IN)

def readData():
    return str(s) + "," + a + "," + str(p)

# 時間計測開始
time_sta = time.perf_counter()

#ボタンが押されるまでループ
while GPIO.input(stopbtn) == 0 :
    time.sleep(0.1)

os.system('./AquesTalkPi ' + '洗顔の終了を確認しました。' + '| aplay')

os.system('./AquesTalkPi ' + '次のモーニングルーティーンは換気です。' + '| aplay')

os.system('./AquesTalkPi ' + 'ベランダへ移動しましょう。' + '| aplay')

# 時間計測終了
time_end = time.perf_counter()
# 経過時間（秒）
p = time_end- time_sta
print(p)

readData()

GPIO.cleanup()