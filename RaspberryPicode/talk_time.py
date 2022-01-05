import time
import os
import random

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
    talks = '現在時刻は、' + hhs + u'じ、'+ mms + u'ふん、' + sss + u'びょうです'
    return talks#.encode('utf-8')

#--------------- main routine ---------------
while 1:
    #時刻をしゃべる
    try:
        print(time2strings())
        os.system('./AquesTalkPi ' + time2strings() + '| aplay')
        #wait_min = random.randint(2, 10)
        #一定時間待つ
        time.sleep(10)
    except:
        pass
        #wait_min = 1

