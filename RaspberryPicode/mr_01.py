import RPi.GPIO as GPIO
import subprocess
import time
import os

stopbtn = 25
s = 1
a = "起床"


GPIO.setmode(GPIO.BCM)
GPIO.setup(stopbtn, GPIO.IN)

def readData():
    return str(s) + "," + a + "," + str(p)

def time2strings():
    talk10 = [u'', u'じゅう', u'にじゅう', u'さんじゅう', u'よんじゅう', u'ごじゅう']
    talk01 = [u'', u'いち', u'に', u'さん', u'よん', u'ご', u'ろく', u'なな', u'はち', u'きゅう']

    jikoku = time.localtime()
    hh = jikoku.tm_hour
    mm = jikoku.tm_min
    ss = jikoku.tm_sec

    hh10 = int(hh / 10)
    hh01 = hh - hh10 * 10
    mm10 = int(mm / 10)
    mm01 = mm - mm10 * 10
    ss10 = int(ss / 10)
    ss01 = ss- ss10 * 10

    hhs = talk10[hh10] + talk01[hh01]
    mms = talk10[mm10] + talk01[mm01]
    sss = talk10[ss10] + talk01[ss01]
    if hhs == '': hhs = u'れい'
    if mms == '': mms = u'れい'
    if sss == '': sss = u'れい'
    talks = '現在時刻は、' + hhs + u'じ、'+ mms + u'ふんです'
    #talks = '現在時刻は、' + hhs + u'じ、'+ mms + u'ふん、' + sss + u'びょうです'
    return talks#.encode('utf-8')

# 時間計測開始
time_sta = time.perf_counter()

#ボタンが押されるまでループ
while GPIO.input(stopbtn) == 0 :

    #アラーム鳴動
    subprocess.call("aplay /home/pi/Alarm_1/alarm.wav", shell = True)

os.system('./AquesTalkPi ' + 'おはようございます' + '| aplay')

os.system('./AquesTalkPi ' + time2strings() + '| aplay')

os.system('./AquesTalkPi ' + 'モーニングルーティーンを始めましょう' + '| aplay')

os.system('./AquesTalkPi ' + '最初のモーニングルーティーンは、洗面台に移動して顔を洗いましょう' + '| aplay')

# 時間計測終了
time_end = time.perf_counter()
# 経過時間（秒）
p = time_end- time_sta
print(p)

readData()

GPIO.cleanup()