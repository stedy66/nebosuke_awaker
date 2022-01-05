# -*- coding: utf-8 -*-
#!/usr/bin/python
import urllib.request
import urllib.parse
import mr_01

csv = mr_01.readData()
list = csv.split(",")

#ステップ_ID
step   = list[0]
#行動
action = list[1]
#経過時間
period = list[2]

posturl = 'http://nebosuke.sakura.ne.jp/nebosuke_awaker/log_write.php'
#送信先設定
postrawdata = {'step':step,'action':action,'period':period}
#送信データセット
postdata = urllib.parse.urlencode(postrawdata)
postbyte = postdata.encode('utf-8')
#byte変換
response = urllib.request.urlopen(posturl, postbyte)
#post実行
body = response.read().decode('utf-8')
print(body)
#返り値表示cr