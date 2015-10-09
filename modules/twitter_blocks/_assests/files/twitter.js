     // var delay = 3500; // you can change it
     // var count = 6;    // How many items to template - must match the no of items from twitter
      var showing = 3;  // How much items to show at a time
      var i = 0;
      var toShow = 0;
      var bottom = false;
      
      function move(i) {
        return function() {
          $('#feed'+i).css('display', 'block').appendTo('#twitter_block_body');
        }
      }
      
      function shiftNext() {
       // next = down + i
       // prev = up - i
        
        // if there is another item
        if($('#feed'+i).length > 0){
            
            
             // hide top item
		if((i - showing) > -1 ){
			binitem = i - showing;
			$('#feed'+binitem).slideUp("slow");
		}           
            
		// show next item
		$('#feed'+i).slideUp("fast", move(i));
			//console.log('incr i');
			i++;
		}else{
           	// ran out of feeds, so show link  
            // alert('ran out');
				
			// show next item - will need to take this out if we have previous
             	
		if(!bottom){
                    binitem = i - showing;
                    $('#feed'+binitem).slideUp("slow");
                    // console.log('incr i');
                    bottom = true;
		
				
           
                $('#lastfeed').slideUp("fast"); 
                $('#lastfeed').css('display', 'block').appendTo('#twitter_block_body');
             //   console.log('last feed');
                }
               
               // bottom = true;
            
          
        }   
        
     //   console.log('i is' + i);
       
         
      }    
      
         
      function shiftPrev() {
       // next = down + i
       // prev = up - i
       if(bottom){
           bottom = false;
       }else{}
        i--;
      
        // display top item
        x = i - showing;
        console.log('i - showing = ' + x);
        if((i - showing) > -1 ){
            binitem = i - showing   ;
            $('#feed'+binitem).slideDown("slow");
        
        
        // if there is another item
        if($('#feed'+i).length > 0){
             // show next item
             console.log('there is another item');
             $('#feed'+i).slideDown("fast", move(i)); 
             
        }
        
        
        // $('#lastfeed').hide();
        
        }else{
            // exit with no effect - just counter the --
            i++;
        }
       console.log('i is' + i);
       
         
      }   
      
           
      function prevTweet(){
          shiftPrev();     
      }
           
      function nextTweet(){   
          shiftNext();         
      }
      
      function preload_tweets(){          
        for (var j=0;j<showing;j++){
          // $('#feed'+j).css("display","block").prependTo('#twitter_block_body');     
           $('#feed'+j).remove().css('display', 'block').appendTo('#twitter_block_body'); 
        }        
        i = showing;  
            
      }
      
      
      
      $(document).ready(function() {          
         preload_tweets();
         // setTimeout('shift(true)', delay);
               
      });