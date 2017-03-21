/**
* サーバの時間を取得する
*
*/
function getUTCDateByServer(){
　var r;
  r = new XMLHttpRequest;
  if(r){
    r.open ('HEAD' , '#' ,false);
    r.send(null);
    return new Date(r.getResponseHeader('Date'));
  }else{
    return null;
  }
}

/**
* 日時表示
*
*/
function setDateTime(Sec)
{
	var dateT = ["日","月","火","水","木","金","土"];
	var nowDate = getUTCDateByServer();
	nowDate.setSeconds(nowDate.getSeconds()+Sec);

	//年,月,日,時,分,秒取得
	var YY = nowDate.getFullYear();
	var MM = nowDate.getMonth() + 1;
	var DD = nowDate.getDate();
	var hh = nowDate.getHours();
	var mi = nowDate.getMinutes();
	var ss = nowDate.getSeconds();
	var wd = dateT[nowDate.getDay()];

	MM = ('0' + MM).slice(-2);
	DD = ('0' + DD).slice(-2);
	hh = ('0' + hh).slice(-2);
	mi = ('0' + mi).slice(-2);

	document.getElementById("date").textContent = YY + '/' + MM + '/' + DD + ' ( '+ wd +' ) ';
	document.getElementById("time").textContent = hh + ':' + mi;

	if(document.getElementById("date_before").textContent == "" || document.getElementById("date_before").textContent == null)
		document.getElementById("date_before").textContent = document.getElementById("date").textContent;

	if(document.getElementById("date_before").textContent == document.getElementById("date").textContent)
		setTimeout("setDateTime(1)", 1000);//1秒後に再度実行
	else
		window.location.reload();	//ページをリロード
}