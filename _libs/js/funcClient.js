function MM_preloadImages() { //v3.0
  var d=document; if(d.images){ if(!d.MM_p) d.MM_p=new Array();
    var i,j=d.MM_p.length,a=MM_preloadImages.arguments; for(i=0; i<a.length; i++)
    if (a[i].indexOf("#")!=0){ d.MM_p[j]=new Image; d.MM_p[j++].src=a[i];}}
}

function MM_swapImgRestore() { //v3.0
  var i,x,a=document.MM_sr; for(i=0;a&&i<a.length&&(x=a[i])&&x.oSrc;i++) x.src=x.oSrc;
}

function MM_findObj(n, d) { //v4.01
  var p,i,x;  if(!d) d=document; if((p=n.indexOf("?"))>0&&parent.frames.length) {
    d=parent.frames[n.substring(p+1)].document; n=n.substring(0,p);}
  if(!(x=d[n])&&d.all) x=d.all[n]; for (i=0;!x&&i<d.forms.length;i++) x=d.forms[i][n];
  for(i=0;!x&&d.layers&&i<d.layers.length;i++) x=MM_findObj(n,d.layers[i].document);
  if(!x && d.getElementById) x=d.getElementById(n); return x;
}

function MM_swapImage() { //v3.0
  var i,j=0,x,a=MM_swapImage.arguments; document.MM_sr=new Array; for(i=0;i<(a.length-2);i+=3)
   if ((x=MM_findObj(a[i]))!=null){document.MM_sr[j++]=x; if(!x.oSrc) x.oSrc=x.src; x.src=a[i+2];}
}

function openWin(name, w, h) {
	var sw = screen.availWidth;
	var sh = screen.availHeight;
	var x = Math.ceil( (sw  - w) / 2 );
	var y = Math.ceil( (sh - h) / 2 );
	mwin = window.open('',name,'width='+w+',height='+h+',left='+x+',top='+y);
	mwin.focus();
	return mwin;		
}
function openReport(name, w, h) {
	var sw = screen.availWidth;
	var sh = screen.availHeight;
	var x = Math.ceil( (sw  - w) / 2 );
	var y = Math.ceil( (sh - h) / 2 );
	mwin = window.open('',name,'width='+w+',height='+h+',left='+x+',top='+y+',resizable=yes');
	mwin.focus();
	return mwin;		
}

//Disable Enter Key
function DisableEnterKey() {	
	if (window.event.keyCode == 13) { //Press Enter
		if (window.document.activeElement.tagName != "TEXTAREA") {
			window.event.returnValue = false;
		}
	}	
	return;
}
	
//------------------------
// Re Format to Integer
// Ex. 0123 reFrmat to 123
//------------------------
function ReFmtInt(inp,formx,obj) {	
	document.forms(formx).item(obj).value = parseFloat(inp);	
	return;	
}

//----------------------------
// Re Format to Decimal
// Ex. .0123 reFrmat to 0.0123
//----------------------------
function ReFmtDec(inp,formx,objx) {

	if (parseFloat(inp) == 0) {
		document.forms(formx).item(objx).value = "0.00"
	}
	else {		
		document.forms(formx).item(objx).value = parseFloat(inp);	
		var new_VALUE = document.forms(formx).item(objx).value;	
		if (new_VALUE.indexOf(".") == -1) {
			document.forms(formx).item(objx).value = document.forms(formx).item(objx).value+".00"				
		}			
	}
	return;	
}

//------------------------
// Trim String
//------------------------
function Trim(STRING){
	STRING = LTrim(STRING);
	return RTrim(STRING);
}
function RTrim(STRING){
	while(STRING.charAt((STRING.length -1))==" "){
		STRING = STRING.substring(0,STRING.length-1);
	}
	return STRING;
}
function LTrim(STRING){
	while(STRING.charAt(0)==" "){
		STRING = STRING.replace(STRING.charAt(0),"");
	}
	return STRING;
}

function DisKey() {
	window.event.returnValue = false
	return;
}

//-------------------------------------
// KeyDate: accept key 0123456789/ only	
//-------------------------------------	
function KeyDate() {
	charallow = "0123456789/"
	keychar = String.fromCharCode(window.event.keyCode);
		
	if (charallow.indexOf(keychar) == -1) {
		window.event.returnValue = false
		return;
	}
}

//-------------------------------------------	
// KeyInt Accept Key Integer Only 0123456789-
// Not allow Duplicate "-"
//-------------------------------------------
function KeyInt() {
	var ActiveField = document.activeElement.value
	charallow = "0123456789-"
	keychar = String.fromCharCode(window.event.keyCode);
		
	if (charallow.indexOf(keychar) == -1) {
		window.event.returnValue = false
		return;
	}
	else {
		if (keychar == "-") {
			if (ActiveField.indexOf("-") > -1) {
				window.event.returnValue = false
				return;
			}
		}
	}
}

//---------------------------------------	
// KeyDec Accept Key Numeric 0123456789-.
// Not allow Duplicate -.
//---------------------------------------
function KeyDec() {
	var ActiveField = document.activeElement.value
	var charallow = "0123456789-."
	keychar = String.fromCharCode(window.event.keyCode);
	
	if (charallow.indexOf(keychar) == -1) {
		window.event.returnValue = false
		return;
	}
	else {
		if (keychar == ".") {
			if (ActiveField.indexOf(".") > -1) {
				window.event.returnValue = false
				return;
			}
		}
		if (keychar == "-") {
			if (ActiveField.indexOf("-") > -1) {
				window.event.returnValue = false
				return;
			}
		}
	
	}
}
	
//-----------------------------------------	
// Check correct Decimal format
// VALUE = Decimal string
// nDec =  Decinal point (0=no limit) 
//----------------------------------------
function CheckDec(VALUE,nDec) {
	var chr;
	var charallow = "0123456789-.";
	for(var i = 0; i < VALUE.length;i ++){
		chr = VALUE.substr(i,1)
		if (charallow.indexOf(chr) == -1) {return false;}
	}
	if (VALUE.indexOf("-") > -1) {
		if (VALUE.substr(0,1) != "-") {return false;}
	}		
	if (VALUE.length == 1) {
		if (VALUE == "-" || VALUE == ".") {return false;}
	}
	if (nDec > 0) {
		if (VALUE.indexOf(".") > -1) {
			pPos = VALUE.indexOf(".");
			suffix = VALUE.substr(pPos+1,VALUE.length);
			if (suffix.length > nDec) {
				return false;
			}
		}	
	}
	else { 
		return true;
	}		
	return true;
}
	
//-----------------------------------------
// CheckInt Check correct format of integer
//-----------------------------------------
function CheckInt(VALUE) {
	var chr;
	var charallow = "0123456789-";
	for(var i = 0; i < VALUE.length;i ++){
		chr = VALUE.substr(i,1)
		if (charallow.indexOf(chr) == -1) {return false;}
	}
	if (VALUE.indexOf("-") > -1) {
		if (VALUE.substr(0,1) != "-") {return false;}
	}		
	if (VALUE.length == 1) {
		if (VALUE == "-") {return false;}
	}
	return true;
}

function checkmailchr(field)
        {
                var chars='0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ_@.'
                var temp

                for (var i=0;i<field.length;i++)
                {
                        temp=field.substring(i,i+1)
                        if (chars.indexOf(temp,0)==-1)
                        {
                               
                                return false
                        }
                }
                return true;
        }



        function isValidEmail(field)
        {
                if ((field.indexOf('@') != -1 ) && (field.indexOf('.') != -1))
                {
                        var symchars1 = '@'
                        var symchars2 = '.'
                        var tempcount1 = 0
                        var tempcount2 = 0

                        for (var i=0;i<field.length;i++)
                        {
                                if (symchars1 == field.substring(i,i+1))
                                {
                                        tempcount1 = i;
                                }
                                if (symchars2 == field.substring(i,i+1))
                                {
                                        tempcount2 = i;
                                }
                        }

                        if (tempcount1 > tempcount2)
                        {
                                
                                return false;
                        }
                }
                else
                {
                       
                        return false;
                }
                if (checkmailchr(field) == false)
                {
                      
                        return false;
                }
                return true;
        }
        
function Left(str, n){
	if (n <= 0)
	    return "";
	else if (n > String(str).length)
	    return str;
	else
	    return String(str).substring(0,n);
}
function Right(str, n){
    if (n <= 0)
       return "";
    else if (n > String(str).length)
       return str;
    else {
       var iLen = String(str).length;
       return String(str).substring(iLen, iLen - n);
    }
}


function PopupPic(sPicURL, title,w,h) { 
    var winl = (screen.width - w) / 2;
    var wint = (screen.height - h) / 2;
    winprops = 'height=100,width=100,top='+wint+',left='+winl+'resizable=1,scrollbars=1'
  window.open("../incs/showpic.asp?"+sPicURL+"&"+title+"", "", winprops)
  } 

function isInt(s) {
	return (s.toString().search(/^[0-9]+$/) == 0);
}

function RadioIsCheck(radioObj) {
	var radioLength = radioObj.length;		
	var radiochecked = false;
	for(var i = 0; i < radioLength; i++) {
		if(radioObj[i].checked) {
			radiochecked = true;
		}
	}
	if (radiochecked==true) {return true;}
	else {return false;}		
}
function ResetRadio(radioObj) {
	var radioLength = radioObj.length;	
	for(var i = 0; i < radioLength; i++) {
		radioObj[i].checked = false;
		radioObj[i].style.backgroundColor="";			
	}			
}

function RadioHighLight(radioObj) {
	var radioLength = radioObj.length;				
	for(var i=0; i<radioLength; i++){			
		if(radioObj[i].checked) { 
			radioObj[i].style.backgroundColor='red'; 
		}
		else {
			radioObj[i].style.backgroundColor="";
		}
	}		
}
function RadioHighLightColor(radioObj,color) {
	var radioLength = radioObj.length;				
	for(var i=0; i<radioLength; i++){			
		if(radioObj[i].checked) { 
			radioObj[i].style.backgroundColor=color; 
		}
		else {
			radioObj[i].style.backgroundColor="";
		}
	}		
}

function getRadioValue(radioObj) {
	var radioLength = radioObj.length;
	for(var i=0; i<radioLength; i++){			
		if(radioObj[i].checked) { 								
			return radioObj[i].value;
		}
	}
	return "";	
}
function hl(ref,classname) {		
	{ref.className=classname;}	
}
function instring(aPattern,aWord) {
	if (aPattern.indexOf(aWord)!=-1) {		
		return true;
	}
	else {		
		return false;
	}
}
function md5(str) {
  //  discuss at: http://phpjs.org/functions/md5/
  // original by: Webtoolkit.info (http://www.webtoolkit.info/)
  // improved by: Michael White (http://getsprink.com)
  // improved by: Jack
  // improved by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
  //    input by: Brett Zamir (http://brett-zamir.me)
  // bugfixed by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
  //  depends on: utf8_encode
  //   example 1: md5('Kevin van Zonneveld');
  //   returns 1: '6e658d4bfcb59cc13f96c14450ac40b9'

  var xl;

  var rotateLeft = function(lValue, iShiftBits) {
    return (lValue << iShiftBits) | (lValue >>> (32 - iShiftBits));
  };

  var addUnsigned = function(lX, lY) {
    var lX4, lY4, lX8, lY8, lResult;
    lX8 = (lX & 0x80000000);
    lY8 = (lY & 0x80000000);
    lX4 = (lX & 0x40000000);
    lY4 = (lY & 0x40000000);
    lResult = (lX & 0x3FFFFFFF) + (lY & 0x3FFFFFFF);
    if (lX4 & lY4) {
      return (lResult ^ 0x80000000 ^ lX8 ^ lY8);
    }
    if (lX4 | lY4) {
      if (lResult & 0x40000000) {
        return (lResult ^ 0xC0000000 ^ lX8 ^ lY8);
      } else {
        return (lResult ^ 0x40000000 ^ lX8 ^ lY8);
      }
    } else {
      return (lResult ^ lX8 ^ lY8);
    }
  };

  var _F = function(x, y, z) {
    return (x & y) | ((~x) & z);
  };
  var _G = function(x, y, z) {
    return (x & z) | (y & (~z));
  };
  var _H = function(x, y, z) {
    return (x ^ y ^ z);
  };
  var _I = function(x, y, z) {
    return (y ^ (x | (~z)));
  };

  var _FF = function(a, b, c, d, x, s, ac) {
    a = addUnsigned(a, addUnsigned(addUnsigned(_F(b, c, d), x), ac));
    return addUnsigned(rotateLeft(a, s), b);
  };

  var _GG = function(a, b, c, d, x, s, ac) {
    a = addUnsigned(a, addUnsigned(addUnsigned(_G(b, c, d), x), ac));
    return addUnsigned(rotateLeft(a, s), b);
  };

  var _HH = function(a, b, c, d, x, s, ac) {
    a = addUnsigned(a, addUnsigned(addUnsigned(_H(b, c, d), x), ac));
    return addUnsigned(rotateLeft(a, s), b);
  };

  var _II = function(a, b, c, d, x, s, ac) {
    a = addUnsigned(a, addUnsigned(addUnsigned(_I(b, c, d), x), ac));
    return addUnsigned(rotateLeft(a, s), b);
  };

  var convertToWordArray = function(str) {
    var lWordCount;
    var lMessageLength = str.length;
    var lNumberOfWords_temp1 = lMessageLength + 8;
    var lNumberOfWords_temp2 = (lNumberOfWords_temp1 - (lNumberOfWords_temp1 % 64)) / 64;
    var lNumberOfWords = (lNumberOfWords_temp2 + 1) * 16;
    var lWordArray = new Array(lNumberOfWords - 1);
    var lBytePosition = 0;
    var lByteCount = 0;
    while (lByteCount < lMessageLength) {
      lWordCount = (lByteCount - (lByteCount % 4)) / 4;
      lBytePosition = (lByteCount % 4) * 8;
      lWordArray[lWordCount] = (lWordArray[lWordCount] | (str.charCodeAt(lByteCount) << lBytePosition));
      lByteCount++;
    }
    lWordCount = (lByteCount - (lByteCount % 4)) / 4;
    lBytePosition = (lByteCount % 4) * 8;
    lWordArray[lWordCount] = lWordArray[lWordCount] | (0x80 << lBytePosition);
    lWordArray[lNumberOfWords - 2] = lMessageLength << 3;
    lWordArray[lNumberOfWords - 1] = lMessageLength >>> 29;
    return lWordArray;
  };

  var wordToHex = function(lValue) {
    var wordToHexValue = '',
      wordToHexValue_temp = '',
      lByte, lCount;
    for (lCount = 0; lCount <= 3; lCount++) {
      lByte = (lValue >>> (lCount * 8)) & 255;
      wordToHexValue_temp = '0' + lByte.toString(16);
      wordToHexValue = wordToHexValue + wordToHexValue_temp.substr(wordToHexValue_temp.length - 2, 2);
    }
    return wordToHexValue;
  };

  var x = [],
    k, AA, BB, CC, DD, a, b, c, d, S11 = 7,
    S12 = 12,
    S13 = 17,
    S14 = 22,
    S21 = 5,
    S22 = 9,
    S23 = 14,
    S24 = 20,
    S31 = 4,
    S32 = 11,
    S33 = 16,
    S34 = 23,
    S41 = 6,
    S42 = 10,
    S43 = 15,
    S44 = 21;

  str = this.utf8_encode(str);
  x = convertToWordArray(str);
  a = 0x67452301;
  b = 0xEFCDAB89;
  c = 0x98BADCFE;
  d = 0x10325476;

  xl = x.length;
  for (k = 0; k < xl; k += 16) {
    AA = a;
    BB = b;
    CC = c;
    DD = d;
    a = _FF(a, b, c, d, x[k + 0], S11, 0xD76AA478);
    d = _FF(d, a, b, c, x[k + 1], S12, 0xE8C7B756);
    c = _FF(c, d, a, b, x[k + 2], S13, 0x242070DB);
    b = _FF(b, c, d, a, x[k + 3], S14, 0xC1BDCEEE);
    a = _FF(a, b, c, d, x[k + 4], S11, 0xF57C0FAF);
    d = _FF(d, a, b, c, x[k + 5], S12, 0x4787C62A);
    c = _FF(c, d, a, b, x[k + 6], S13, 0xA8304613);
    b = _FF(b, c, d, a, x[k + 7], S14, 0xFD469501);
    a = _FF(a, b, c, d, x[k + 8], S11, 0x698098D8);
    d = _FF(d, a, b, c, x[k + 9], S12, 0x8B44F7AF);
    c = _FF(c, d, a, b, x[k + 10], S13, 0xFFFF5BB1);
    b = _FF(b, c, d, a, x[k + 11], S14, 0x895CD7BE);
    a = _FF(a, b, c, d, x[k + 12], S11, 0x6B901122);
    d = _FF(d, a, b, c, x[k + 13], S12, 0xFD987193);
    c = _FF(c, d, a, b, x[k + 14], S13, 0xA679438E);
    b = _FF(b, c, d, a, x[k + 15], S14, 0x49B40821);
    a = _GG(a, b, c, d, x[k + 1], S21, 0xF61E2562);
    d = _GG(d, a, b, c, x[k + 6], S22, 0xC040B340);
    c = _GG(c, d, a, b, x[k + 11], S23, 0x265E5A51);
    b = _GG(b, c, d, a, x[k + 0], S24, 0xE9B6C7AA);
    a = _GG(a, b, c, d, x[k + 5], S21, 0xD62F105D);
    d = _GG(d, a, b, c, x[k + 10], S22, 0x2441453);
    c = _GG(c, d, a, b, x[k + 15], S23, 0xD8A1E681);
    b = _GG(b, c, d, a, x[k + 4], S24, 0xE7D3FBC8);
    a = _GG(a, b, c, d, x[k + 9], S21, 0x21E1CDE6);
    d = _GG(d, a, b, c, x[k + 14], S22, 0xC33707D6);
    c = _GG(c, d, a, b, x[k + 3], S23, 0xF4D50D87);
    b = _GG(b, c, d, a, x[k + 8], S24, 0x455A14ED);
    a = _GG(a, b, c, d, x[k + 13], S21, 0xA9E3E905);
    d = _GG(d, a, b, c, x[k + 2], S22, 0xFCEFA3F8);
    c = _GG(c, d, a, b, x[k + 7], S23, 0x676F02D9);
    b = _GG(b, c, d, a, x[k + 12], S24, 0x8D2A4C8A);
    a = _HH(a, b, c, d, x[k + 5], S31, 0xFFFA3942);
    d = _HH(d, a, b, c, x[k + 8], S32, 0x8771F681);
    c = _HH(c, d, a, b, x[k + 11], S33, 0x6D9D6122);
    b = _HH(b, c, d, a, x[k + 14], S34, 0xFDE5380C);
    a = _HH(a, b, c, d, x[k + 1], S31, 0xA4BEEA44);
    d = _HH(d, a, b, c, x[k + 4], S32, 0x4BDECFA9);
    c = _HH(c, d, a, b, x[k + 7], S33, 0xF6BB4B60);
    b = _HH(b, c, d, a, x[k + 10], S34, 0xBEBFBC70);
    a = _HH(a, b, c, d, x[k + 13], S31, 0x289B7EC6);
    d = _HH(d, a, b, c, x[k + 0], S32, 0xEAA127FA);
    c = _HH(c, d, a, b, x[k + 3], S33, 0xD4EF3085);
    b = _HH(b, c, d, a, x[k + 6], S34, 0x4881D05);
    a = _HH(a, b, c, d, x[k + 9], S31, 0xD9D4D039);
    d = _HH(d, a, b, c, x[k + 12], S32, 0xE6DB99E5);
    c = _HH(c, d, a, b, x[k + 15], S33, 0x1FA27CF8);
    b = _HH(b, c, d, a, x[k + 2], S34, 0xC4AC5665);
    a = _II(a, b, c, d, x[k + 0], S41, 0xF4292244);
    d = _II(d, a, b, c, x[k + 7], S42, 0x432AFF97);
    c = _II(c, d, a, b, x[k + 14], S43, 0xAB9423A7);
    b = _II(b, c, d, a, x[k + 5], S44, 0xFC93A039);
    a = _II(a, b, c, d, x[k + 12], S41, 0x655B59C3);
    d = _II(d, a, b, c, x[k + 3], S42, 0x8F0CCC92);
    c = _II(c, d, a, b, x[k + 10], S43, 0xFFEFF47D);
    b = _II(b, c, d, a, x[k + 1], S44, 0x85845DD1);
    a = _II(a, b, c, d, x[k + 8], S41, 0x6FA87E4F);
    d = _II(d, a, b, c, x[k + 15], S42, 0xFE2CE6E0);
    c = _II(c, d, a, b, x[k + 6], S43, 0xA3014314);
    b = _II(b, c, d, a, x[k + 13], S44, 0x4E0811A1);
    a = _II(a, b, c, d, x[k + 4], S41, 0xF7537E82);
    d = _II(d, a, b, c, x[k + 11], S42, 0xBD3AF235);
    c = _II(c, d, a, b, x[k + 2], S43, 0x2AD7D2BB);
    b = _II(b, c, d, a, x[k + 9], S44, 0xEB86D391);
    a = addUnsigned(a, AA);
    b = addUnsigned(b, BB);
    c = addUnsigned(c, CC);
    d = addUnsigned(d, DD);
  }

  var temp = wordToHex(a) + wordToHex(b) + wordToHex(c) + wordToHex(d);

  return temp.toLowerCase();
}
function replaceAll(str, oldchar, newchar) {
	return str.split(oldchar).join(newchar);
}
