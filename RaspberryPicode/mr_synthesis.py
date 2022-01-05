import RPi.GPIO as GPIO
import subprocess
import time
import os
import urllib.request
import urllib.parse

STOP_SW = 25
PIR_PIN = 24
LED_PIN = 17

GPIO.setmode(GPIO.BCM)
GPIO.setup(STOP_SW, GPIO.IN, pull_up_down=GPIO.PUD_DOWN)
GPIO.setup(PIR_PIN, GPIO.IN, pull_up_down=GPIO.PUD_OFF)
GPIO.setup(LED_PIN, GPIO.OUT)



#読み上げ時間作成
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

#サクラサーバーへPOST
def sakurapost(step,action,period):
    #送信先設定
    #posturl = 'http://silverturtle21.sakura.ne.jp/mr_test4/log_write.php'
    posturl = 'http://nebosuke.sakura.ne.jp/nebosuke_awaker/log_write.php'
    #送信データセット
    postrawdata = {'step':step,'action':action,'period':period}
    #byte変換
    postdata = urllib.parse.urlencode(postrawdata)
    postbyte = postdata.encode('utf-8')
    #post実行
    response = urllib.request.urlopen(posturl, postbyte)
    #返り値表示
    body = response.read().decode('utf-8')
    print(body)


#モーニングルーティーン１
#MR1のstep,action
s = 1
a = "起床"

# 時間計測開始
time_sta = time.perf_counter()

#ボタンが押されるまでループ
while GPIO.input(STOP_SW) == 0 :

    #アラーム鳴動
    subprocess.call("aplay /home/pi/Alarm_1/alarm.wav", shell = True)
    
#読み上げ
os.system('./AquesTalkPi ' + 'おはようございます' + '| aplay')
os.system('./AquesTalkPi ' + time2strings() + '| aplay')
os.system('./AquesTalkPi ' + 'モーニングルーティーンを始めましょう' + '| aplay')
os.system('./AquesTalkPi ' + '最初のモーニングルーティーンは、洗面台に移動して顔を洗いましょう' + '| aplay')

# 時間計測終了
time_end = time.perf_counter()
# 経過時間（秒）
p = time_end- time_sta
print(p)

#サクラサーバーにPOST
sakurapost(s,a,p)


#モーニングルーティーン２
#MR2のstep,action
s = 2
a = "洗面台へ移動"


# 時間計測開始
time_sta = time.perf_counter()

#Motion_sensor検知までループ
while GPIO.input(PIR_PIN) == GPIO.LOW:
    time.sleep(0.1)
    
#LED点灯
GPIO.output(LED_PIN, GPIO.HIGH)
#読み上げ
os.system('./AquesTalkPi ' + '洗面台へ移動を確認しました。' + '| aplay')
os.system('./AquesTalkPi ' + '顔を洗いましょう。' + '| aplay')

# 時間計測終了
time_end = time.perf_counter()
# 経過時間（秒）
p = time_end- time_sta
print(p)

#サクラサーバーにPOST
sakurapost(s,a,p)

#time.sleep(3)
#LED滅灯
GPIO.output(LED_PIN, GPIO.LOW)


#モーニングルーティーン３
#MR3のstep,action
s = 3
a = "洗顔"

# 時間計測開始
time_sta = time.perf_counter()

#ボタンが押されるまでループ
while GPIO.input(STOP_SW) == 0 :
    time.sleep(0.1)

#読み上げ
os.system('./AquesTalkPi ' + '洗顔の終了を確認しました。' + '| aplay')
os.system('./AquesTalkPi ' + '次のモーニングルーティーンは換気です。' + '| aplay')
os.system('./AquesTalkPi ' + '窓へ移動しましょう。' + '| aplay')

# 時間計測終了
time_end = time.perf_counter()
# 経過時間（秒）
p = time_end- time_sta
print(p)

#サクラサーバーにPOST
sakurapost(s,a,p)


#モーニングルーティーン４
#MR3のstep,action
s = 4
a = "窓へ移動"

# 時間計測開始
time_sta = time.perf_counter()

#Motion_sensorまでループ
while GPIO.input(PIR_PIN) == GPIO.LOW:
    time.sleep(0.1)

#LED点灯
GPIO.output(LED_PIN, GPIO.HIGH)
#読み上げ
os.system('./AquesTalkPi ' + '窓へ移動を確認しました。' + '| aplay')
os.system('./AquesTalkPi ' + '窓を開けて換気しましょう。' + '| aplay')

# 時間計測終了
time_end = time.perf_counter()

# 経過時間（秒）
p = time_end- time_sta
print(p)
#
sakurapost(s,a,p)
#LED滅灯
GPIO.output(LED_PIN, GPIO.LOW)


#モーニングルーティーン５
#MR3のstep,action
s = 5
a = "窓を開ける"

# 時間計測開始
time_sta = time.perf_counter()

#ボタンが押されるまでループ
while GPIO.input(STOP_SW) == 0 :
    time.sleep(0.1)

#読み上げ
os.system('./AquesTalkPi ' + '換気の終了を確認しました。' + '| aplay')
os.system('./AquesTalkPi ' + '次のモーニングルーティーンは、ちょうしょくです。' + '| aplay')
os.system('./AquesTalkPi ' + 'キッチンへ移動しましょう。' + '| aplay')

# 時間計測終了
time_end = time.perf_counter()
# 経過時間（秒）
p = time_end- time_sta
print(p)

#サクラサーバーにPOST
sakurapost(s,a,p)



GPIO.cleanup()