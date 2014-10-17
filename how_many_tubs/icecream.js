
var items = [[1,"Bottles of Olive Oil","Bottle of Olive Oil",4],


		[1,"Cases of Beer","Case of Beer",2],


		[0,"Dollars","Dollar",10],


		[1,"Meals at Tick Tock","Meal at Tick Tock",1]];




		
window.onload=start;





function start() 

{
	preImage = new Image(); 

	preImage.src = "aaron.png";


	var select = document.getElementById("factor");


	for (var i = 0; i < items.length; i++)

	{
		select.options[select.options.length] = new Option(items[i][1], i);
	}

}





function testForEnter() 
{    
	if (event.keyCode == 13) 
	{        
		event.cancelBubble = true;
		event.returnValue = false;
		doConvertion();
    }
} 




function doConvertion()

{

	var num = document.frm.number.value-0;

	var x = document.frm.factor.value-0;

	var outtext = getText(num,x);

	document.getElementById("top").src="aaron.png";
	var htext = document.getElementById("hiddenText");

	htext.innerHTML=outtext;
	
	var textdiv = document.getElementById("text");
	textdiv.style.fontSize = 28;
	var r = textdiv.offsetWidth/textdiv.offsetHeight;
	var s = parseInt(textdiv.style.fontSize.replace("px",""));
	
	while((r < 1.1) || (r > 1.7))
	{
		textdiv.style.fontSize = s*r*.65 + "px";
		r = textdiv.offsetWidth/textdiv.offsetHeight;
		s = parseInt(textdiv.style.fontSize.replace("px",""));
	}
}





function getText(num,x)

{

	var t1 = "Yo Dude! ";

	var t2 = getT2(num,x);

	var t3 = getT3(num,x);

	return t1 + t2 + t3;

}





function getT2(num,x)

{

	if (num==1)

	{

	  var t2out = num + " " + items[x][2] + "! That's like ";

	}

	else

	{

	  var t2out = num + " " + items[x][1] + "! That's like ";

	}



	return t2out;

}





function getT3(num,x)

{

	if (items[x][0]===1)

	{

		var a = num*items[x][3];

	}

	else

	{

		var a = num/items[x][3];

	}



	if (a==1)

	{

		var t3out = "1 Tub of Ice Cream!";

	}

	else

	{

		var t3out = a + " Tubs of Ice Cream!";

	}



	return t3out;

}

