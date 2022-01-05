import urllib.request
import urllib.parse

def sakurapost(step,action,period):
    #送信先設定
    posturl = 'http://silverturtle21.sakura.ne.jp/mr_test4/log_write.php'
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
    