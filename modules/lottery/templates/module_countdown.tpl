
          <div class="jackpot-block">
              <h1> This template is in the module only !</h1>
            <p>This week’s <span>Rollover Jackpot</span></p>
            <ul>
              <li><span class="label-txt">1st Chance</span><span>&pound;{$smarty.const.LOTTERY_ROLL_OVER_FIRST_CHANCE}</span></li>
              <li><span class="label-txt">2nd Chance</span><span>&pound;{$smarty.const.LOTTERY_ROLL_OVER_SECOND_CHANCE}</span></li>
            </ul>
          </div>
          <div class="blurb">
            <ul>
              <li>&pound;{$smarty.const.LOTTERY_TICKET_COST} for 2 draws</li>
              <li>Win up to &pound;{$smarty.const.LOTTERY_PRIZE_TOTAL}</li>
              <li>Play every Friday</li>
            </ul>
          </div>
          <div class="counter-wrapper">
            <p>Time left to play this week’s lottery:</p>
            <div class="counter">
              <div id="first_countdown" style="position:relative; width:255px; height:100px;"></div>
            </div>
          </div>
{literal}
<script>
$(document).ready(function () {
  if($("#first_countdown").length > 0){
    counter("#first_countdown");	
  }
  function counter(target){
	$(target).ResponsiveCountdown({
	  target_date:"{/literal}{$next_draw_date} {$smarty.const.LOTTERY_DRAW_TIME}{literal}:00",
	  time_zone:1,target_future:true,
	  set_id:0,pan_id:0,day_digits:1,
	  fillStyleSymbol1:"rgba(255, 255, 255, 1)",
	  fillStyleSymbol2:"rgba(255, 255, 255, 1)",
	  fillStylesPanel_g1_1:"rgba(186, 186, 17, 1)",
	  fillStylesPanel_g1_2:"rgba(105, 107, 25, 1)",
	  fillStylesPanel_g2_1:"rgba(214, 19, 53, 1)",
	  fillStylesPanel_g2_2:"rgba(133, 11, 32, 1)",
	  text_color:"rgba(151, 151, 150, 1)",
	  text_glow:"rgba(0,0,0,0)",
	  show_ss:true,show_mm:true,
	  show_hh:true,show_dd:true,
	  f_family:"Verdana",show_labels:true,
	  type3d:"single",max_height:300,
	  days_long:"DAYS",days_short:"d",
	  hours_long:"HOURS",hours_short:"hh",
	  mins_long:"MINUTES",mins_short:"mm",
	  secs_long:"SECONDS",secs_short:"ss",
	  min_f_size:9,max_f_size:30,
	  spacer:"none",groups_spacing:0,text_blur:2,
	  f_weight:"bold"
	});	
  }
});
</script>
{/literal}
<!-- unused: {$smarty.const.LOTTERY_WINNER_NAME} {$smarty.const.LOTTERY_DRAW_DAY} {$days_to_go}{$hours_to_go}{$mins_to_go} {* price is set in shop/classes/basket add_item_to_basket - look for LOTTERY_TICKET_COST *}-->