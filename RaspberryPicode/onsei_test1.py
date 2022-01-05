#!/usr/bin/env python
# -*- coding: utf-8 -*-
import os
import time
import datetime

# 時間計測開始
#time_sta = time.perf_counter()

os.system('./AquesTalkPi ' + 'おはようございます' + '| aplay')

os.system('./AquesTalkPi ' + '現在時刻は06時01分です' + '| aplay')

os.system('./AquesTalkPi ' + 'モーニングルーティーンを始めましょう' + '| aplay')

os.system('./AquesTalkPi ' + '最初のモーニングルーティーンは、洗面台に移動して顔を洗いましょう' + '| aplay')

os.system('./AquesTalkPi ' + '1121' + '| aplay')
