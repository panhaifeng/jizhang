// JavaScript Document
function $G(Read_Id) { return document.getElementById(Read_Id) }

var openedObjId=null
function Effect(ObjectId, height){
	
	if (openedObjId){
		$G(openedObjId+"tab").innerHTML = "<img src='Resource/Image/elbow-end-plus.gif'>";
		Start(openedObjId,'Close');
	}
	if(openedObjId!=ObjectId){
		$G(ObjectId+"tab").innerHTML = "<img src='Resource/Image/elbow-end-minus.gif'>";
		Start(ObjectId,'Opens');
		openedObjId=ObjectId
	}else{
		openedObjId=null;
	}
	/*if ($G(ObjectId+"tab").innerHTML == "+"){
		Start(ObjectId,'Opens');
		$G(ObjectId+"tab").innerHTML = "-"
	} else if($G(ObjectId+"tab").innerHTML == "-"){
		Start(ObjectId,'Close');
		$G(ObjectId+"tab").innerHTML = "+"		
	}*/
}

function Start(ObjId,method){
  var BoxHeight = $G(ObjId).offsetHeight; //对象高度
  //alert(ObjId); 
  //alert(BoxHeight); 
  var MinHeight = 10;//定义最小高度
  var MaxHeight = 100;//定义最大高度
  
  var BoxAddMax = 1;//递增量初始值
  var Every_Add = 0.35;//每次的递(减)增量[数值越大速度越快]
  var Reduce    = (BoxAddMax - Every_Add);
  var Add       = (BoxAddMax + Every_Add);
  
  //折叠
  if (method == "Close"){
	var Alter_Close = function(){//构建虚拟的[递减]循环
	  BoxAddMax /= Reduce;
	  BoxHeight -= BoxAddMax;
	  if (BoxHeight <= MinHeight){
		$G(ObjId).style.display = "none";
		window.clearInterval(BoxAction);
	  }else{
		  $G(ObjId).style.height = BoxHeight;
	  }
	}
	var BoxAction = window.setInterval(Alter_Close,1);
  }
  //展开
  else if (method == "Opens"){
	var Alter_Opens = function(){
	  BoxAddMax *= Add;
	  BoxHeight += BoxAddMax;
	  if (BoxHeight >= MaxHeight){
		//$G(ObjId).style.height = MaxHeight;
		window.clearInterval(BoxAction);
	  }else{
		$G(ObjId).style.display= "block";
		//$G(ObjId).style.height = BoxHeight;
	  }
	}
	var BoxAction = window.setInterval(Alter_Opens,1);
  }
}