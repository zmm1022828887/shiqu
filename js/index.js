var Layer='';
var iLayerMaxNum=1000;
var a;
var b;
var c;
function Move(Object,event){
	Layer=Object.id;
	if(document.all){
		document.getElementById(Layer).setCapture();
		b=event.x-document.getElementById(Layer).style.pixelLeft;
		c=event.y-document.getElementById(Layer).style.pixelTop;
	}else if(window.captureEvents){
		window.captureEvents(Event.MOUSEMOVE|Event.MOUSEUP);
		b=event.layerX;
		c=event.layerY;
	};
	if(Layer!="Layer"+a){
		document.getElementById(Layer).style.zIndex=iLayerMaxNum;
		iLayerMaxNum=iLayerMaxNum+1;
	}
}
function Close(n){
	var e='Layer'+n;											
	document.getElementById(e).style.display='none';
}
function Show(n){
	var e='Layer'+n;
	document.getElementById(e).style.zIndex =iLayerMaxNum+1;
	document.getElementById("aspk").style.display = "block";
	document.getElementById("aspk").style.zIndex = iLayerMaxNum;
	var size = getPageSize();
	document.getElementById("aspk").style.width = size[0];
	document.getElementById("aspk").style.height = size[1];	
}	