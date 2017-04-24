
/******************************************
---------> startrisna@gmail.com :::-->> 01714-118636
******************************************/



var ID="EDT"; // the textbox id to apply the layout to
var ConvertFrom = "bijoy" // Options are, "bijoy", "somewherein", "boisakhi"



/******************************************
******************************************/
function ChangeKeyboarLayoutStatus()
{
    // var keyboard_ley = document.getElementById("keyboard");
    var field = document.getElementsByName('KeyboardLayoutOption');

    if(KeyBoardLayout==1 || EnglishKeyboard==true)
        { // keyboard_ley.innerHTML = "Current keyboard layout: <b>English</b>";
        field[0].checked = true; }
    else if(KeyBoardLayout==2)
        { // keyboard_ley.innerHTML = "Current keyboard layout: <b>Bijoy Bangla</b>";
        field[KeyBoardLayout-1].checked = true; }
    else if(KeyBoardLayout==3)
        { // keyboard_ley.innerHTML = "Current keyboard layout: <b>Somewhere-in Phonetic Bangla</b>";
        field[KeyBoardLayout-1].checked = true; }
    else if(KeyBoardLayout==4)
        { // keyboard_ley.innerHTML = "Current keyboard layout: <b>Avro Phonetic Bangla</b>";
        field[KeyBoardLayout-1].checked = true; }
    else if(KeyBoardLayout==5)
        { // keyboard_ley.innerHTML = "Current keyboard layout: <b>Unijoy Bangla</b>";
        field[KeyBoardLayout-1].checked = true; }
}


/******************************************
******************************************/
function ChangeConverterStatus()
{
    var field = document.getElementsByName('ConversionOption');
    
    // Options are, "bijoy", "somewherein", "boisakhi"
    if(ConvertFrom=="bijoy")
        { field[0].checked = true; }
    else if(ConvertFrom=="somewherein")
        { field[1].checked = true; }
    else if(ConvertFrom=="boisakhi")
        { field[2].checked = true; }
}





/******************************************
******************************************/
function KeyboardLayoutOptionClick(event)
{
    var field = document.getElementsByName('KeyboardLayoutOption');

    for (var counter = 0; counter < field.length; counter++)
    {
        if (field[counter].checked)
        {
            KeyBoardLayout = counter+1; 
            ChangeKeyboarLayoutStatus();
            var myFld = document.getElementById(ID);
            myFld.focus();
            break;
        }
    }
} // end function KeyboardLayoutOptionClick







/******************************************
******************************************/
function ConvertFromTextArea(idcvt)
{
    var str = document.getElementById(idcvt).value;
    str = ConvertToUnicode(ConvertFrom, str);
    Insert(document.getElementById(ID), str);
} // end function ConvertFromTextArea


/******************************************
******************************************/
function ConvertToTextArea(idcvt)
{
    var str = document.getElementById(ID).value;
    str = ConvertToASCII(ConvertFrom, str);
    Insert(document.getElementById(idcvt), str);    
} // end function ConvertToTextArea



/******************************************
******************************************/
function ClearTextArea(idtxt)
{
    var elem = document.getElementById(idtxt);
    elem.value = "";
    elem.focus();
} // end function ClearTextArea



/******************************************

******************************************/
function ChangeConvertOptionStatus()
{
    var field = document.getElementsByName('ConversionOption');
    var convertarea = document.getElementById('CONVERTEDT');

    if(ConvertFrom == "bijoy")
    {
        convertarea.style.fontFamily = "SutonnyMJ";
        convertarea.style.fontSize = "14pt";
        convertarea.style.width = 400;
        // convertarea.cols = 80;

        field[0].checked = true;
    }
    else if(ConvertFrom == "somewherein")
    {
        convertarea.style.fontFamily = "SushreeMJ";
        convertarea.style.fontSize = "14pt";
        convertarea.style.width = 300;
        // convertarea.cols = 80;
        field[1].checked = true;
    }
    else if(ConvertFrom == "boisakhi")
    {
        convertarea.style.fontFamily = "Boishakhi";
        convertarea.style.fontSize = "12pt";
        convertarea.style.width = 300;
        // convertarea.cols = 80; // why so large value!? Don't ask me.
        field[2].checked = true;
    }
    
} // end function ChangeConvertOptionStatus




/******************************************

******************************************/
function ConvertOptionChange(event)
{
    var field = document.getElementsByName('ConversionOption');

    for (var counter = 0; counter < field.length; counter++)
    {
        if (field[counter].checked)
        {
            if(counter == 0)
                ConvertFrom = "bijoy";
            else if(counter == 1)
                ConvertFrom = "somewherein";
            else if(counter == 2)
                ConvertFrom = "boisakhi";
            break;
        }
    }
    ChangeConvertOptionStatus();
}

/******************************************

******************************************/
function OnPageLoad()
{
    ChangeKeyboarLayoutStatus();
    ChangeConverterStatus();

    var myFld = document.getElementById(ID);
    var unicodefontLabel = document.getElementById("unicodefont");

    if(IE)
    {
        unicodefontLabel.innerHTML = "Download the unicode font from <a href=SutonnyBanglaOMJ.ttf>here</a>."
        myFld.style.fontFamily = "SutonnyBanglaOMJ";
    }
    else
    {
        unicodefontLabel.innerHTML = "Download the unicode font from <a href=SolaimanLipi_29-05-06.ttf>here</a>."
        myFld.style.fontFamily = "SolaimanLipi";
    }
    myFld.style.width = 400;
    var convertarea = document.getElementById('CONVERTEDT');
    convertarea.style.width = 400;
}



