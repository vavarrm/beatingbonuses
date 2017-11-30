<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
	<head>
		<meta http-equiv="content-type" content="text/html;charset=ISO-8859-1">
		<title>Casino Hold'em Strategy Calculator</title>
		<link title="currentstyle" href="css/pages.css" rel="stylesheet" media="screen">
		<link rel="stylesheet" href="css/layout.css">

		<script language="javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js"></script>
		<script language="javascript" src="js/menu.js"></script>
		<script language="javascript" src="js/prototype.js"></script>
        <style type="text/css" id="header_css">
        .headers {background-image: url(images/hd2.jpg);
		          background-repeat: repeat-x;
	              background-position: top;}
		</style>
		<style type="text/css">
			#deck 
			{ 
				border: 1px solid brown;
				padding: 0px;
				margin: 0px;
				background:#101010;
			}
			.small_card
			{
				border: 0px; 
				margin: 0px;
				padding: 1px;
				width: 22px;
				height: 31px;	
				cursor: hand;
			}
			.large_card 
			{
				border: 0px; 
				margin: 0px;			
				width: 71px; 
				height: 95px;
			}
		</style>
		<script type="text/javascript">
		    var wakeup;
		 
			function _clear()
			{
				$('stats').style.display = 'none';
				$('output').style.display = 'none';
				$('handp').update("");
				$('handc').update("");
                var t = document.getElementById("ttab");
				t.rows[2].style.display = 'none';
                t.rows[3].style.display = 'none';

				$('payout_display').style.display = 'none';
				for (var i=0; i < 5; i++) $('cardImages'+i).src = "images/cards/large/back.gif";
				for (var i=0; i < 5; i++) document.calc.cards[i].value = "";
                updateCards();

				return true;
			}

			function _load()
			{
			    // $('payout_display').style.display = 'none';
				// $('result').style.display = 'none';
				// $('result2').style.display = 'none';
				
				$('payout_display').css('display', 'none');
				$('result').css('display', 'none');
				$('result2').css('display', 'none');
				//_clear();
				
				wakeup = 1;

				return true;
			}

			function _showpaytable()
			{
				if ($('payout_display').style.display == '') $('payout_display').style.display = 'none';
				else $('payout_display').style.display = '';
				//$('result').style.display = 'none';
				return true;
			}			
						
			function isCard(card)
			{
			    card = card.toLowerCase();
				if ((!card) || (card.length != 2)) return false;
				var ranks = '123456789tjqka';
				var suits = 'hsdc';
				var rank = card.charAt(0);
			    var suit = card.charAt(1);
				if ((ranks.indexOf(rank) == -1) || (suits.indexOf(suit) == -1)) return false;
				return true;
			}

			function convertCard(card)
			{
				card = card.toLowerCase();
				var ranks = '23456789tjqk1';
				var suits = 'hsdc';
				var rank = card.charAt(0);
			    var suit = card.charAt(1);
				
				var output = 4*ranks.indexOf(rank) + suits.indexOf(suit) +1;
				
				return output;
			}

			function selectCard(i, card)
			{
			    if (wakeup == 1) {
					wakeup = 0;
				}
			
	            name = "cardSmall";				
				for (var j=0; j < 5; j++){
				  oldCard = document.calc.cards[j].value;
				  console.log(oldCard);
					if (oldCard != "")  
					{
					  $('#cardImages'+j).attr('src' ,'images/cards/large/2d.gif');	
					  console.log($('#cardImages'+j).attr('src'));
					}
				}
														
				if (!card)
				{
					document.calc.cards[i].value = "";
					$('cardImages'+i).src = "images/cards/large/back.gif";
					oldCard = document.calc.cards[i].value;
					document.calc.cards[i].value= "";
				}
				else
				{				
					var num_cards = 0;
					for (var j=0; j < 5; j++) {
					   oldCard = document.calc.cards[j].value;
					   if (j!=i && oldCard == card) {
				          $(name+oldCard).src = "images/cards/small/"+oldCard+".gif";
			    	      document.calc.cards[j].value = "";
					      $('cardImages'+j).src = "images/cards/large/back.gif";					      
					      return false;
				       }
					   if (i==j || isCard(oldCard)) num_cards++;
				    }
					if (i>=5) i = 4;
					var cardfix = card;
					if (cardfix == 'ac') cardfix = '1c';
					if (cardfix == 'as') cardfix = '1s';
					if (cardfix == 'ad') cardfix = '1d';
					if (cardfix == 'ah') cardfix = '1h';
					document.calc.cards[i].value = cardfix;
					$('cardImages'+i.toString()).src = "images/cards/large/" + card.toLowerCase() + ".gif";
				}
                updateCards();
				if (num_cards==5) _submit();
				return true;
			}

			function updateCards()
			{
	            name = "cardSmall";				  				  			
				var suits = ['h', 's', 'd', 'c'];
				var ranks = ['2', '3', '4', '5', '6', '7', '8', '9', 't', 'j', 'q', 'k', '1'];
				for (var i=0; i < suits.length; i++)
				{
					for (var j=0; j < ranks.length; j++)
					{
                       $(name+ranks[j]+suits[i]).src = "images/cards/small/" +ranks[j]+suits[i]+ ".gif";
					}
				}
				for (var i=0; i<5; i++)
				{
				   card = document.calc.cards[i].value;
				   if (isCard(card)) $(name+card.toLowerCase()).src = "images/cards/small/back.gif";
				}
			}

			function _check()
			{
				var cards = document.calc.cards;
				for (var i=0; i < 2; i++)
				{
					if (!isCard(cards[i].value))
					{
						alert("You must select 2 player cards.");
						return false;
					}
				}
				for (var i=2; i < 5; i++)
				{
					if (!isCard(cards[i].value))
					{
						alert("You must select 3 community cards.");
						return false;
					}
				}
				for (var i = 0; i < document.calc.optimization.length; i++) {
					if (document.calc.optimization[i].checked) {
						optimization = document.calc.optimization[i].value;
						break;
					}
				}
				if (!optimization){
					alert('You must select an optimization strategy');
					return false;
				}				
				return true;
			}


           
		    function display_hand(start, finish) {
			   var hand = "";
				for (var i=start; i <= finish; i++) {
				   card = document.calc.cards[i].value;
				   if (card != "") hand += "<img src=\"images/cards/small/" + card.toLowerCase() + ".gif\">";
				}
			    return hand;
			}
			
			function _submit()
			{
				if (!_check()) return false;
				//$('playercards').update('');
				//$('communitycards').update('');
				$('ev').update('');
				$('decision').update('');				
                $('payout_display').style.display = 'none';
                var t = document.getElementById("ttab");
				t.rows[2].style.display = '';
                t.rows[3].style.display = '';
					
				cardValues = new Array(document.calc.cards.length);
				var card;
				senderIndex = 0;
				
				for (var i=0; i < cardValues.length; i++) {
				   card = document.calc.cards[i].value;
				   if (card != "") {
				      cardValues[senderIndex] = convertCard(card);
					  senderIndex++;
				   }
				}
				if ($('caribbean').checked) cardValues[0] = -cardValues[0];
				var playerCards = cardValues.slice(0, 2);
				var communityCards = cardValues.slice(2, 5);				
				var payouts = new Array(document.calc.payouts.length + 4);
				for (var i=0; i < payouts.length; i++) {
					if (i < document.calc.payouts.length) payouts[i] = document.calc.payouts[i].value;
					else payouts[i] = payouts[i-1];
				}

				$('handp').update(display_hand(0,1));
				$('handc').update(display_hand(2,4));	
				//$('playercards').update("Player Cards: " + playerCards.join(' ').toUpperCase());
				//$('communitycards').update("Community Cards: " + communityCards.join(' ').toUpperCase());
				var URL = "casinoholdem2_exec.php?player=" + playerCards.join(' ') + "&board=" + communityCards.join(' ') + "&payouts=" + payouts.join(' ');							
				//var URL = "vp_hand_exec.php?player=" + playerCards.join(' ') + "&game=" + game + "&payouts=" + payouts.join(' ');
				
				for (var i=0; i < 5; i++) $('cardImages'+i).src = "images/cards/large/" + document.calc.cards[i].value.toLowerCase() + ".gif";

				new Ajax.Request(URL, {
					method: 'post',
					onSuccess: function(transport) {					
						var output = transport.responseText.split(' ');						
						if (output.length >= 1) {
						    $('result').style.display = '';
						    $('result2').style.display = '';
							$('stats').style.display = '';
							$('output').style.display = '';
							$('error').style.display = 'none';
							var ev = Number(output[0]);
							var prob = new Array(22);
							for (i=0; i<22; i++) prob[i] = Number(output[1+i]);
							
							$('ev').update(ev.toFixed(2));
							if (optimization == 'hand') {	// Optimization per hand
								if (ev > -1) $('decision').update('Raise');
								else {
								  $('decision').update('Fold');
								  $('cardImages0').src = "images/cards/large/back.gif";
							      $('cardImages1').src = "images/cards/large/back.gif";
								}
							}
							else {	// Optimization per unit wagered
								if ((ev + 0.0164 > -1 && !($('caribbean').checked)) || (ev + 0.0234 > -1 && ($('caribbean').checked))) $('decision').update('Raise');
								else {
								  $('decision').update('Fold');
								  $('cardImages0').src = "images/cards/large/back.gif";
							      $('cardImages1').src = "images/cards/large/back.gif";
								}								
							}
							
							$('ev2').update(ev.toFixed(2));
							
							var percent;
							var o2 = document.getElementById("otab2");
                            for (var i=0; i<23; i++) {
				              o2.rows[i+1].style.display = 'none';
				            }
							o2.rows[21].style.display = '';
							
							 var names = ["Royal Flush", "Royal Flush", "Straight Flush", "Straight Flush", "4 of a Kind", "4 of a Kind", "Full House", "Full House", 
							   "Flush", "Flush", "Straight", "Straight", "3 of Kind", "3 of Kind", "2 Pair", "2 Pair", "Pair", "Pair", "High Card", "High Card", "Tie", "Lose"];
							
							var j=0;
							for (var k=0; k<22; k++) {
							    if (prob[j]>0) {
								  if (j>=20) o2.rows[j+2].style.display = '';
								  else o2.rows[j+1].style.display = '';		     
								  $('hname' + j).update(names[k]);
								  if (k == 20) $('pay' + j).update("0");
								  else if (k == 21) $('pay' + j).update("-3");
								  else {
								    if (k%2 == 0) $('pay' + j).update(parseInt(payouts[k>>1])+2);
									else {
									  if ($('caribbean').checked) $('pay' + j).update(1);
									  else $('pay' + j).update(payouts[k>>1]);
									}
								  }
								  percent = 100/prob[j];
								  if (percent==100) $('prob' + j).update(percent.toFixed(0) + '%');
								  else if (percent>99.9999) $('prob' + j).update(percent.toFixed(5) + '%');
								  else if (percent>99.999) $('prob' + j).update(percent.toFixed(4) + '%');
								  else if (percent>99.99) $('prob' + j).update(percent.toFixed(3) + '%');
								  else if (percent>99.9) $('prob' + j).update(percent.toFixed(2) + '%');
								  else if (percent>10) $('prob' + j).update(percent.toFixed(1) + '%');
								  else if (percent>1) $('prob' + j).update(percent.toFixed(2) + '%');
								  else if (percent>0.1) $('prob' + j).update(percent.toFixed(3) + '%');
								  else if (percent>0.01) $('prob' + j).update(percent.toFixed(4) + '%');
								  else if (percent>0.001) $('prob' + j).update(percent.toFixed(4) + '%');
								  else if (percent>0.0001) $('prob' + j).update(percent.toFixed(5) + '%');
								  else if (percent>0.00001) $('prob' + j).update(percent.toFixed(6) + '%');
								  else $('prob' + j).update(percent.toFixed(7) + '%');
							      if (prob[j]>= 10) $('rate' + j).update(prob[j].toFixed(0));
								  else $('rate' + j).update(prob[j].toFixed(1));
								}
								//$('prob' + j).update("1%");								
							    j++;																							
							}

						}
						else {
							$('stats').style.display = 'none';
							$('output').style.display = 'none';
							$('error').style.display = '';
							$('error').update(output[0]);
						}
					}
				});				
			}						
		</script>
	</head>
	<body onLoad="_load();">
			<script type="text/javascript">
				var cardIndex;								
				function onDeckClick(card)
				{
					console.log(card);
                    senderIndex = 0;
				    for (var i = 0; i< 5; i++) {
		               if (!isCard(document.calc.cards[i].value)) break;
					   else senderIndex++;
				    }
    	            cardIndex = senderIndex;

                    selectCard(cardIndex,card);
					return true;
				}
			</script>
		</div>
		<div id="main">
			<br/>
			<br/>
			<div align="center">		
    <h1><font size="5">CASINO HOLD'EM STRATEGY CALCULATOR</font></h1>

			
    <p>&nbsp;</p>

			<p><font size="2" face="Arial, Helvetica, sans-serif">Select two player cards 
    and three community cards. You can make the card selections by clicking on 
    the deck of small cards or by entering the cards manually using the text boxes. 
    The text box entry is in the form 2D = 2 of diamonds, TS = ten of spades, 
    KH = king of hearts, etc. Click on the corresponding small or large card to 
    clear the selection for that card. The calculator will return the strategy 
    decision that has the highest EV, either raise or fold. The optimization selection 
    only alters strategy decisions for close calls when the EV is slightly below 
    -1. 
    </font></p><p><font size="2" face="Arial, Helvetica, sans-serif">&nbsp;</font></p><font size="2" face="Arial, Helvetica, sans-serif">
  
  <table><tbody><tr><td valign="top">
		<form name="calc" onsubmit="return false" style="margin: 0px; padding: 0px;">
		<div style="width: 470px; font: 11px Verdana; border: 2px dotted; padding: 10px;">
        <div align="center">

        <font size="2"><strong>Casino Hold'em Strategy Calculator</strong></font><br><br> 

		 <div style="border-top: solid 1px; margin-left: 10px; margin-right: 10px; margin-bottom: 10px; margin-top: 15px;"></div>

<div id="deck" align="center" nowrap="" style="font-size: 0px; width: 313px;">
    <img id="cardSmall2h" class="small_card" src="images/cards/small/2h.gif" height="31" width="22" onclick="onDeckClick('2h')">
    <img id="cardSmall3h" class="small_card" src="images/cards/small/3h.gif" height="31" width="22" onclick="onDeckClick('3h')">
    <img id="cardSmall4h" class="small_card" src="images/cards/small/4h.gif" height="31" width="22" onclick="onDeckClick('4h')">
    <img id="cardSmall5h" class="small_card" src="images/cards/small/5h.gif" height="31" width="22" onclick="onDeckClick('5h')">
    <img id="cardSmall6h" class="small_card" src="images/cards/small/6h.gif" height="31" width="22" onclick="onDeckClick('6h')">
    <img id="cardSmall7h" class="small_card" src="images/cards/small/7h.gif" height="31" width="22" onclick="onDeckClick('7h')">
    <img id="cardSmall8h" class="small_card" src="images/cards/small/8h.gif" height="31" width="22" onclick="onDeckClick('8h')">
    <img id="cardSmall9h" class="small_card" src="images/cards/small/9h.gif" height="31" width="22" onclick="onDeckClick('9h')">
    <img id="cardSmallth" class="small_card" src="images/cards/small/th.gif" height="31" width="22" onclick="onDeckClick('th')">
    <img id="cardSmalljh" class="small_card" src="images/cards/small/jh.gif" height="31" width="22" onclick="onDeckClick('jh')">
    <img id="cardSmallqh" class="small_card" src="images/cards/small/qh.gif" height="31" width="22" onclick="onDeckClick('qh')">
    <img id="cardSmallkh" class="small_card" src="images/cards/small/kh.gif" height="31" width="22" onclick="onDeckClick('kh')">
    <img id="cardSmall1h" class="small_card" src="images/cards/small/1h.gif" height="31" width="22" onclick="onDeckClick('1h')">
    <br><br>
    <img id="cardSmall2s" class="small_card" src="images/cards/small/2s.gif" height="31" width="22" onclick="onDeckClick('2s')">
    <img id="cardSmall3s" class="small_card" src="images/cards/small/3s.gif" height="31" width="22" onclick="onDeckClick('3s')">
    <img id="cardSmall4s" class="small_card" src="images/cards/small/4s.gif" height="31" width="22" onclick="onDeckClick('4s')">
    <img id="cardSmall5s" class="small_card" src="images/cards/small/5s.gif" height="31" width="22" onclick="onDeckClick('5s')">
    <img id="cardSmall6s" class="small_card" src="images/cards/small/6s.gif" height="31" width="22" onclick="onDeckClick('6s')">
    <img id="cardSmall7s" class="small_card" src="images/cards/small/7s.gif" height="31" width="22" onclick="onDeckClick('7s')">
    <img id="cardSmall8s" class="small_card" src="images/cards/small/8s.gif" height="31" width="22" onclick="onDeckClick('8s')">
    <img id="cardSmall9s" class="small_card" src="images/cards/small/9s.gif" height="31" width="22" onclick="onDeckClick('9s')">
    <img id="cardSmallts" class="small_card" src="images/cards/small/ts.gif" height="31" width="22" onclick="onDeckClick('ts')">
    <img id="cardSmalljs" class="small_card" src="images/cards/small/js.gif" height="31" width="22" onclick="onDeckClick('js')">
    <img id="cardSmallqs" class="small_card" src="images/cards/small/qs.gif" height="31" width="22" onclick="onDeckClick('qs')">
    <img id="cardSmallks" class="small_card" src="images/cards/small/ks.gif" height="31" width="22" onclick="onDeckClick('ks')">
    <img id="cardSmall1s" class="small_card" src="images/cards/small/1s.gif" height="31" width="22" onclick="onDeckClick('1s')">
    <br><br>
    <img id="cardSmall2d" class="small_card" src="images/cards/small/2d.gif" height="31" width="22" onclick="onDeckClick('2d')">
    <img id="cardSmall3d" class="small_card" src="images/cards/small/3d.gif" height="31" width="22" onclick="onDeckClick('3d')">
    <img id="cardSmall4d" class="small_card" src="images/cards/small/4d.gif" height="31" width="22" onclick="onDeckClick('4d')">
    <img id="cardSmall5d" class="small_card" src="images/cards/small/5d.gif" height="31" width="22" onclick="onDeckClick('5d')">
    <img id="cardSmall6d" class="small_card" src="images/cards/small/6d.gif" height="31" width="22" onclick="onDeckClick('6d')">
    <img id="cardSmall7d" class="small_card" src="images/cards/small/7d.gif" height="31" width="22" onclick="onDeckClick('7d')">
    <img id="cardSmall8d" class="small_card" src="images/cards/small/8d.gif" height="31" width="22" onclick="onDeckClick('8d')">
    <img id="cardSmall9d" class="small_card" src="images/cards/small/9d.gif" height="31" width="22" onclick="onDeckClick('9d')">
    <img id="cardSmalltd" class="small_card" src="images/cards/small/td.gif" height="31" width="22" onclick="onDeckClick('td')">
    <img id="cardSmalljd" class="small_card" src="images/cards/small/jd.gif" height="31" width="22" onclick="onDeckClick('jd')">
    <img id="cardSmallqd" class="small_card" src="images/cards/small/qd.gif" height="31" width="22" onclick="onDeckClick('qd')">
    <img id="cardSmallkd" class="small_card" src="images/cards/small/kd.gif" height="31" width="22" onclick="onDeckClick('kd')">
    <img id="cardSmall1d" class="small_card" src="images/cards/small/1d.gif" height="31" width="22" onclick="onDeckClick('1d')">
<br><br>
    <img id="cardSmall2c" class="small_card" src="images/cards/small/2c.gif" height="31" width="22" onclick="onDeckClick('2c')">
    <img id="cardSmall3c" class="small_card" src="images/cards/small/3c.gif" height="31" width="22" onclick="onDeckClick('3c')">
    <img id="cardSmall4c" class="small_card" src="images/cards/small/4c.gif" height="31" width="22" onclick="onDeckClick('4c')">
    <img id="cardSmall5c" class="small_card" src="images/cards/small/5c.gif" height="31" width="22" onclick="onDeckClick('5c')">
    <img id="cardSmall6c" class="small_card" src="images/cards/small/6c.gif" height="31" width="22" onclick="onDeckClick('6c')">
    <img id="cardSmall7c" class="small_card" src="images/cards/small/7c.gif" height="31" width="22" onclick="onDeckClick('7c')">
    <img id="cardSmall8c" class="small_card" src="images/cards/small/8c.gif" height="31" width="22" onclick="onDeckClick('8c')">
    <img id="cardSmall9c" class="small_card" src="images/cards/small/9c.gif" height="31" width="22" onclick="onDeckClick('9c')">
    <img id="cardSmalltc" class="small_card" src="images/cards/small/tc.gif" height="31" width="22" onclick="onDeckClick('tc')">
    <img id="cardSmalljc" class="small_card" src="images/cards/small/jc.gif" height="31" width="22" onclick="onDeckClick('jc')">
    <img id="cardSmallqc" class="small_card" src="images/cards/small/qc.gif" height="31" width="22" onclick="onDeckClick('qc')">
    <img id="cardSmallkc" class="small_card" src="images/cards/small/kc.gif" height="31" width="22" onclick="onDeckClick('kc')">
    <img id="cardSmall1c" class="small_card" src="images/cards/small/1c.gif" height="31" width="22" onclick="onDeckClick('1c')">
</div>
	 
        <div style="border-top: solid 1px; margin-left: 10px; margin-right: 10px; margin-bottom: 5px; margin-top: 10px;"></div>
		<div style="text-align: center;">
		  <table align="center" style="text-align: center">
		   <tbody><tr>
		      <td colspan="2"><font size="2"><strong>Player Cards</strong></font></td>
		      <td rowspan="3">&nbsp;&nbsp;</td>						
              <td colspan="3"><font size="2"><strong>Community Cards</strong></font></td>
		   </tr>
            <tr> 
              <td><a href="javascript:void(0)" onclick="selectCard(0, undefined)"><img id="cardImages0" class="large_card" src="images/cards/large/back.gif"></a></td>
              <td><a href="javascript:void(0)" onclick="selectCard(1, undefined)"><img id="cardImages1" class="large_card" src="images/cards/large/back.gif"></a></td>
              <td><a href="javascript:void(0)" onclick="selectCard(2, undefined)"><img id="cardImages2" class="large_card" src="images/cards/large/back.gif"></a></td>
              <td><a href="javascript:void(0)" onclick="selectCard(3, undefined)"><img id="cardImages3" class="large_card" src="images/cards/large/back.gif"></a></td>
              <td><a href="javascript:void(0)" onclick="selectCard(4, undefined)"><img id="cardImages4" class="large_card" src="images/cards/large/back.gif"></a></td>
            </tr>
            <tr> 
              <td><input type="text" name="cards" onchange="return selectCard(0, this.value)" size="2"></td>
              <td><input type="text" name="cards" onchange="return selectCard(1, this.value)" size="2"></td>
              <td><input type="text" name="cards" onchange="return selectCard(2, this.value)" size="2"></td>
              <td><input type="text" name="cards" onchange="return selectCard(3, this.value)" size="2"></td>
              <td><input type="text" name="cards" onchange="return selectCard(4, this.value)" size="2"></td>
            </tr>
          </tbody></table>	
		 <br>
         <div style="text-align: left; margin-left: 60px;">
            <input type="radio" id="handoptimization" name="optimization" value="hand" checked=""><label for="handoptimization"><b>Optimize For Largest Gain Per Hand (standard) </b></label><br>
            <input type="radio" id="wageroptimization" name="optimization" value="wager"><label for="wageroptimization"><b>Optimize For Largest Gain Per Unit Wagered</b></label><br>
		 	<input type="checkbox" id="caribbean" unchecked="" name="caribbean"><label for="caribbean"><b>Use Caribbean Hold'em Rules (RTG variation)</b></label><br>	
         </div>
      <div style="border-top: solid 1px; margin: 10px;"></div>
      <div align="center">
  	     <button onclick="_showpaytable(); return false;">Modify Paytable</button>&nbsp;&nbsp;
	     <button onclick="_clear(); return false;">Clear</button>&nbsp;&nbsp;
	     <button onclick="_submit(); return false;">Calculate</button>		 
	  </div>
		 		 
         <span id="payout_display" style="display: none">
		    <div style="border-top: solid 1px; margin: 10px;"></div>
		    <b>Payout Values:</b> <br>
			<div style="border-top: solid 1px; margin: 10px;"></div>
							<div align="center">
			   <table id="ctab" width="200" style="font-size: 12px; font-family: Verdana, Geneva, sans-serif">
			     <tbody><tr><td><b>Royal flush:</b></td><td><input type="text" name="payouts" value="100" size="5"></td></tr> 
                 <tr><td><b>Straight flush:</b></td><td><input type="text" name="payouts" value="20" size="5"></td></tr> 
                 <tr><td><b>4 of a kind:</b></td><td><input type="text" name="payouts" value="10" size="5"></td></tr> 
                 <tr><td><b>Full house:</b></td><td><input type="text" name="payouts" value="3" size="5"></td></tr> 
                 <tr><td><b>Flush:</b></td><td><input type="text" name="payouts" value="2" size="5"></td></tr> 
                 <tr><td><b>All other:</b></td><td><input type="text" name="payouts" value="1" size="5"></td></tr>
			   </tbody></table> 
			   <br><button onclick="_showpaytable(); return false;">Close Paytable</button>
            </div>
         </span>
		 
      <div id="result" style="display: none; font-size: 12px; font-family: Verdana, Geneva, sans-serif;">
	    <div style="border-top: solid 1px; margin: 10px;"></div>
        <div style="background: #222; padding: 8px; text-align: left;">
          <div align="center">
            <p style="text-align: center" id="error"></p>
            <div id="output">
              <b>&nbsp;<u id="decision"></u></b><br><br>
              <b>Raise EV: <span id="ev"></span></b><br>
              <b>Fold EV: -1.00</b><br>
            </div>
          </div>
	    </div>
	  </div>		 
		 
       </div>
	  </div>
	  </div>
      </form>
</td>
<td width="50">&nbsp;</td>
<td valign="top">	 
      <div id="result2" style="display: none;">
	  <div style="width: 370px; font: 11px Verdana; border: 2px dotted; padding: 10px;">
	  <table align="center" id="ttab" cellpadding="0" style="font-size: 12px; font-family: Verdana, Geneva, sans-serif">
	    <tbody><tr><td colspan="2"><font size="2"><strong>Casino Hold'em Raise Stats</strong></font></td></tr>
	    <tr><td colspan="2"><font size="2"><strong>&nbsp;</strong></font></td></tr>
	    <tr><td nowrap="" align="right"><strong>Player Cards: </strong>&nbsp;</td><td><b><span id="handp"></span></b></td></tr>
	    <tr><td nowrap="" align="right"><strong>Community Cards: </strong>&nbsp;</td><td><b><span id="handc"></span></b></td></tr>
	  </tbody></table>
	  <div id="stats">
      <div style="background: #222; padding: 4px; text-align: left;">
       <div style="border-top: solid 1px; margin: 10px;"></div>
		  <table align="center" cellpadding="3" id="otab2" style="font-size: 12px; font-family: Verdana, Geneva, sans-serif">
		  <tbody><tr>
            <td><b><font color="#C0C0C0">Winning Hand</font></b></td>
			<td width="50"><b><font color="#C0C0C0">Dealer Qual?</font></b></td>
			<td><b><font color="#C0C0C0">Pay</font></b></td>
			<td><b><font color="#C0C0C0">Percentage</font></b></td>
            <td nowrap=""><b><font color="#C0C0C0">1 in ...</font></b></td>
		  </tr>
          <tr><td><b><span id="hname0"></span></b></td><td><b>Yes</b></td><td><b><span id="pay0"></span></b><br></td><td><b><span id="prob0"></span></b><br></td><td><b><span id="rate0"></span></b><br></td></tr>
          <tr><td><b><span id="hname1"></span></b></td><td><b>No</b></td><td><b><span id="pay1"></span></b><br></td><td><b><span id="prob1"></span></b><br></td><td><b><span id="rate1"></span></b><br></td></tr>
          <tr><td><b><span id="hname2"></span></b></td><td><b>Yes</b></td><td><b><span id="pay2"></span></b><br></td><td><b><span id="prob2"></span></b><br></td><td><b><span id="rate2"></span></b><br></td></tr>
          <tr><td><b><span id="hname3"></span></b></td><td><b>No</b></td><td><b><span id="pay3"></span></b><br></td><td><b><span id="prob3"></span></b><br></td><td><b><span id="rate3"></span></b><br></td></tr>
          <tr><td><b><span id="hname4"></span></b></td><td><b>Yes</b></td><td><b><span id="pay4"></span></b><br></td><td><b><span id="prob4"></span></b><br></td><td><b><span id="rate4"></span></b><br></td></tr>
          <tr><td><b><span id="hname5"></span></b></td><td><b>No</b></td><td><b><span id="pay5"></span></b><br></td><td><b><span id="prob5"></span></b><br></td><td><b><span id="rate5"></span></b><br></td></tr>
          <tr><td><b><span id="hname6"></span></b></td><td><b>Yes</b></td><td><b><span id="pay6"></span></b><br></td><td><b><span id="prob6"></span></b><br></td><td><b><span id="rate6"></span></b><br></td></tr>
          <tr><td><b><span id="hname7"></span></b></td><td><b>No</b></td><td><b><span id="pay7"></span></b><br></td><td><b><span id="prob7"></span></b><br></td><td><b><span id="rate7"></span></b><br></td></tr>
          <tr><td><b><span id="hname8"></span></b></td><td><b>Yes</b></td><td><b><span id="pay8"></span></b><br></td><td><b><span id="prob8"></span></b><br></td><td><b><span id="rate8"></span></b><br></td></tr>
          <tr><td><b><span id="hname9"></span></b></td><td><b>No</b></td><td><b><span id="pay9"></span></b><br></td><td><b><span id="prob9"></span></b><br></td><td><b><span id="rate9"></span></b><br></td></tr>
          <tr><td><b><span id="hname10"></span></b></td><td><b>Yes</b></td><td><b><span id="pay10"></span></b><br></td><td><b><span id="prob10"></span></b><br></td><td><b><span id="rate10"></span></b><br></td></tr>
          <tr><td><b><span id="hname11"></span></b></td><td><b>No</b></td><td><b><span id="pay11"></span></b><br></td><td><b><span id="prob11"></span></b><br></td><td><b><span id="rate11"></span></b><br></td></tr>
          <tr><td><b><span id="hname12"></span></b></td><td><b>Yes</b></td><td><b><span id="pay12"></span></b><br></td><td><b><span id="prob12"></span></b><br></td><td><b><span id="rate12"></span></b><br></td></tr>
          <tr><td><b><span id="hname13"></span></b></td><td><b>No</b></td><td><b><span id="pay13"></span></b><br></td><td><b><span id="prob13"></span></b><br></td><td><b><span id="rate13"></span></b><br></td></tr>
          <tr><td><b><span id="hname14"></span></b></td><td><b>Yes</b></td><td><b><span id="pay14"></span></b><br></td><td><b><span id="prob14"></span></b><br></td><td><b><span id="rate14"></span></b><br></td></tr>
          <tr><td><b><span id="hname15"></span></b></td><td><b>No</b></td><td><b><span id="pay15"></span></b><br></td><td><b><span id="prob15"></span></b><br></td><td><b><span id="rate15"></span></b><br></td></tr>
          <tr><td><b><span id="hname16"></span></b></td><td><b>Yes</b></td><td><b><span id="pay16"></span></b><br></td><td><b><span id="prob16"></span></b><br></td><td><b><span id="rate16"></span></b><br></td></tr>
          <tr><td><b><span id="hname17"></span></b></td><td><b>No</b></td><td><b><span id="pay17"></span></b><br></td><td><b><span id="prob17"></span></b><br></td><td><b><span id="rate17"></span></b><br></td></tr>
          <tr><td><b><span id="hname18"></span></b></td><td><b>Yes</b></td><td><b><span id="pay18"></span></b><br></td><td><b><span id="prob18"></span></b><br></td><td><b><span id="rate18"></span></b><br></td></tr>
          <tr><td><b><span id="hname19"></span></b></td><td><b>No</b></td><td><b><span id="pay19"></span></b><br></td><td><b><span id="prob19"></span></b><br></td><td><b><span id="rate19"></span></b><br></td></tr>
           <tr><td colspan="5"><div style="border-top: solid 1px; margin: 0px;"></div></td></tr>
          <tr><td><b><span id="hname20"></span></b></td><td><b>Yes</b></td><td><b><span id="pay20"></span></b><br></td><td><b><span id="prob20"></span></b><br></td><td><b><span id="rate20"></span></b><br></td></tr>
          <tr><td><b><span id="hname21"></span></b></td><td><b>Yes</b></td><td><b><span id="pay21"></span></b><br></td><td><b><span id="prob21"></span></b><br></td><td><b><span id="rate21"></span></b><br></td></tr>
        </tbody></table>	  
       <div style="border-top: solid 1px; margin: 10px;"></div>
		  <table align="center" cellpadding="3" id="otab2" style="font-size: 12px; font-family: Verdana, Geneva, sans-serif">
		  <tbody><tr>
            <td nowrap=""><b><font color="#C0C0C0">Raise EV: </font></b></td>
			<td><b><font color="#C0C0C0">&nbsp;</font></b></td>
            <td nowrap=""><b><font color="#C0C0C0"><span id="ev2"></span></font></b></td>
		  </tr>
        </tbody></table>
      <p style="text-align: center" id="error"></p>
	  </div>
	  </div>
	  </div>
      </div>
</td></tr></tbody></table>
</font>
			</div>
			<br/>
			<br/>
			<p><font size="1" face="Arial">Copyright &copy; 2006-2014 www.beatingbonuses.com. All Rights Reserved.</font></p>
		</div>
	<script language="javascript" src="js/dc.js"></script>
	</body>
</html>