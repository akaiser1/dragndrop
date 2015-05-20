<html>
<head>


<style type="text/css">
	body{
		font-family: Trebuchet MS, Lucida Sans Unicode, Arial, sans-serif;
		/*margin:0px;
		padding:0px;*/
		background-image:url('http://webcursos.uai.cl/local/tutoriales/images/nuevo-logo-wcurso_transp2.gif');
		background-repeat:no-repeat;
		padding-top:85px;					
		overflow:hidden;
		padding-left:10px;
		-moz-user-select:no; 
	}
	
	/* Don't change these options */
	#movableNode{
		position:absolute;
	}
	
	#arrDestInditcator{
		position:absolute;
		display:none;
		width:100px;
	}
	/* End options that shouldn't be changed */

	
	#arrangableNodes1,#movableNode ul{
		padding-left:0px;
		margin-left:0px;
		margin-top:0px;
		padding-top:0px;
	}
	#arrangableNodes2,#movableNode ul{
		padding-left:0px;
		margin-left:0px;
		margin-top:0px;
		padding-top:0px;
	}
	#arrangableNodes1 li,#movableNode li{
		list-style-type:none;
		cursor:default;
	}
	#arrangableNodes2 li,#movableNode li{
		list-style-type:none;
		cursor:default;
	}
	

	</style>
	
	<script type="text/javascript">
	/************************************************************************************************************
	(C) www.dhtmlgoodies.com, October 2005
	
	This is a script from www.dhtmlgoodies.com. You will find this and a lot of other scripts at our website.	
	
	Terms of use:
	You are free to use this script as long as the copyright message is kept intact. However, you may not
	redistribute, sell or repost it without our permission.
	
	Thank you!
	
	www.dhtmlgoodies.com
	Alf Magne Kalleland
	
	************************************************************************************************************/	
	
	var offsetYInsertDiv = -3; // Y offset for the little arrow indicating where the node should be inserted.
	if(!document.all)offsetYInsertDiv = offsetYInsertDiv - 7; 	// No IE

	
	var arrParent = false;
	var arrMoveCont = false;
	var arrMoveCounter = -1;
	var arrTarget = false;
	var arrNextSibling = false;
	var leftPosArrangableNodes = false;
	var widthArrangableNodes = false;
	var nodePositionsY = new Array();
	var nodeHeights = new Array();
	var arrInsertDiv = false;
	var insertAsFirstNode = false;
	var arrNodesDestination = false;
	function cancelEvent()
	{
		return false;
	}
	function getTopPos(inputObj)
	{
		
	  var returnValue = inputObj.offsetTop;
	  while((inputObj = inputObj.offsetParent) != null){
	  	returnValue += inputObj.offsetTop;
	  }
	  return returnValue;
	}
	
	function getLeftPos(inputObj)
	{
	  var returnValue = inputObj.offsetLeft;
	  while((inputObj = inputObj.offsetParent) != null)returnValue += inputObj.offsetLeft;
	  return returnValue;
	}
		
	function clearMovableDiv()
	{
		if(arrMoveCont.getElementsByTagName('il').length>0){
			if(arrNextSibling)arrParent.insertBefore(arrTarget,arrNextSibling); else arrParent.appendChild(arrTarget);			
		}
		
	}
	
	function initMoveNode(e)
	{
		clearMovableDiv();
		if(document.all)e = event;
		arrMoveCounter = 0;
		arrTarget = this;
		if(this.nextSibling)arrNextSibling = this.nextSibling; else arrNextSibling = false;
		timerMoveNode();
		arrMoveCont.parentNode.style.left = e.clientX + 'px';
		arrMoveCont.parentNode.style.top = e.clientY + 'px';
		return false;
		
	}
	function timerMoveNode()
	{
		if(arrMoveCounter>=0 && arrMoveCounter<10){
			arrMoveCounter = arrMoveCounter +1;
			setTimeout('timerMoveNode()',20);
		}
		if(arrMoveCounter>=10){
			arrMoveCont.appendChild(arrTarget);
		}
	}
		
	function arrangeNodeMove(e)
	{
		if(document.all)e = event;
		if(arrMoveCounter<10)return;
		if(document.all && arrMoveCounter>=10 && e.button!=1 && navigator.userAgent.indexOf('Opera')==-1){
			arrangeNodeStopMove();
		}
		
		arrMoveCont.parentNode.style.left = e.clientX + 'px';
		arrMoveCont.parentNode.style.top = e.clientY + 'px';	
		
		var tmpY = e.clientY;
		arrInsertDiv.style.display='none';
		arrNodesDestination = false;
		

		if(e.clientX<leftPosArrangableNodes || e.clientX>leftPosArrangableNodes + widthArrangableNodes)return; 
			
		var subs = arrParent.getElementsByTagName('il');
		for(var no=0;no<subs.length;no++){
			var topPos =getTopPos(subs[no]);
			var tmpHeight = subs[no].offsetHeight;
			
			if(no==0){
				if(tmpY<=topPos && tmpY>=topPos-5){
					arrInsertDiv.style.top = (topPos + offsetYInsertDiv) + 'px';
					arrInsertDiv.style.display = 'block';				
					arrNodesDestination = subs[no];	
					insertAsFirstNode = true;
					return;
				}				
			}
			
			if(tmpY>=topPos && tmpY<=(topPos+tmpHeight)){
				arrInsertDiv.style.top = (topPos+tmpHeight + offsetYInsertDiv) + 'px';
				arrInsertDiv.style.display = 'block';				
				arrNodesDestination = subs[no];
				insertAsFirstNode = false;
				return;
			}				
		}
	}
	
	function arrangeNodeStopMove()
	{
		arrMoveCounter = -1; 
		arrInsertDiv.style.display='none';
		
		if(arrNodesDestination){
			var subs = arrParent.getElementsByTagName('il');
			if(arrNodesDestination==subs[0] && insertAsFirstNode){
				arrParent.insertBefore(arrTarget,arrNodesDestination);		
			}else{
				if(arrNodesDestination.nextSibling){
					arrParent.insertBefore(arrTarget,arrNodesDestination.nextSibling);
				}else{
					arrParent.appendChild(arrTarget);
				}
			}
		}		
		arrNodesDestination = false;
		clearMovableDiv();
	}		
	
	function saveArrangableNodes()
	{
		var nodes = arrParent.getElementsByTagName('il');
		var string = "";
		for(var no=0;no<nodes.length;no++){
			if(string.length>0)string = string + ',';
			string = string + nodes[no].id;		
		}
		
		document.forms[0].hiddenNodeIds.value = string;
		
		// Just for testing
		document.getElementById('arrDebug').innerHTML = 'Ready to save these nodes:<br>' + string.replace(/,/g,',<BR>');	
		
		// document.forms[0].submit(); // Remove the comment in front of this line when you have set an action to the form.
		
	}
	
	function initArrangableNodes()
	{
		arrParent = document.getElementById('arrangableNodes1');
		arrMoveCont = document.getElementById('movableNode').getElementsByTagName('ul')[0]; 
		arrInsertDiv = document.getElementById('arrDestInditcator');
		
		leftPosArrangableNodes = getLeftPos(arrParent);
		arrInsertDiv.style.left = leftPosArrangableNodes - 5 + 'px';
		widthArrangableNodes = arrParent.offsetWidth;
		
		var subs = arrParent.getElementsByTagName('li'); 
		for(var no=0;no<subs.length;no++){
			subs[no].onmousedown = initMoveNode;
			subs[no].onselectstart = cancelEvent;	
		}
	
		document.documentElement.onmouseup = arrangeNodeStopMove;
		document.documentElement.onmousemove = arrangeNodeMove;
		document.documentElement.onselectstart = cancelEvent;
		
	}	
	
	window.onload = initArrangableNodes;
	
	</script>
	
</head>

<body>
<H1>Cruce de horarios</H1>
<h2>Bolsa de personas</h2>
<table>
<tr><td>
<ul id="arrangableNodes1"> 
   <li id="node1">Alumnos</li>
   <li id="node2">Profesores</li>
	<li id="node3">Carreras</li>
  <li id="node4">Autoridades</li>
  <li id="node5">Funcionarios</li>
  <li id="node6">Materia</li>
  <li id="node7">Edificio</li>
  <li id="node8"> </li>	
  <li id="node9"> </li>	
  <li id="node10"> </li>	
  <li id="node11">Extra 4</li>	
  <li id="node12"> </li>	
  <li id="node13"> </li>
	</ul>
	</td>



</table>

<p>
	<a href="#" onclick="saveArrangableNodes();return false">Save</a>
</p>
<div id="movableNode"><ul></ul></div>	
<div id="arrDestInditcator"><img src="imagenes/insert.gif"></div> 
<div id="arrDebug"></div>
<form method="post" action="????">
	<input type="hidden" name="hiddenNodeIds">
</form>


</body>
</html>
