function req(container, reqModule, reqCommand, reqData, reqData2, reqData3, append, callback){
    
    if(append != 1) append=0;
    
    if(!append) {
        var waitingcode = "<div class='reqWaiting'><div>"
        $( container ).html(waitingcode);
    }
    
    $.ajax({
      url: "/req/",
      data: {
        module:  reqModule,
        command: reqCommand,
        data: reqData,
        data2: reqData2,
        data3: reqData3
      },
      success: function( data ) {
          
         pp = data.indexOf("||");
         if(pp > 0){
             gotourl = data.substring(0, pp);
             navigate(gotourl);
         }
         else{
            
            if(container.indexOf(".")!==0 && container.indexOf("#")!==0){
                container =  "#"+container ;
            }
            
            pp = data.indexOf("##");
            if(pp > 0){
                container = data.substring(0, pp);
                data = data.substring(pp+2);
            }
         }

        // JSON RESPONSE -> INDICATES ERROR MESSAGE
        try {
            var jr = $.parseJSON(data);
            if(jr.error){
                try{
                    if(jr.error == -9801){
                        window.location.replace("/app/xMain/login");
                    }
                    else if(jr.error==-9802){
                        window.location.replace(jr.description);
                    }
                    else{
                        displayErrorMsg(jr.error, jr.description, jr.details);
                    }
                }
                catch (err){
                    alert("ERROR: " + jr.   error + "\n\n" + jr.description + "\n\n" + jr.details);
                }
            }
        }
        catch (err) {
            // NO JSON RESPONSE -> RESPONSE FROM MODULE TO BE DISPLAYED
            if(append){
                $( container ).append(data);
                if( jQuery.isFunction(callback) ){
                    callback(data);
                }
            }
            else{
                var afterIn = function(){
                    $( container + ' .focus' ).first().focus().select();
                    
                    if( jQuery.isFunction(callback) ){
                        callback(data);
                    }
                };
                $( container ).hide().html(data).fadeIn(200, afterIn);
            }
            
        }

        
      }
    });
    
}

function executeRequest(reqModule, reqCommand, callBack, reqData, reqData2, reqData3, callbackData=''){
    $.ajax({
      url: "/req/",
      data: {
        module:  reqModule,
        command: reqCommand,
        data: reqData,
        data2: reqData2,
        data3: reqData3
      },
      success: function( data ) {
          if( jQuery.isFunction(callBack)){
            callBack(data, callbackData);
          }
      }
    })
}

jQuery.fn.req2 = function(reqModule, reqCommand, options){
    
    var reqDefaults = {
        module:  reqModule,
        command: reqCommand,
        append: false,
        callback: function(){}
    };
    
    var o = $.extend(o, reqDefaults, options);         // for internal use
    var oData = $.extend(oData, reqDefaults, options); // to send to application  
    
    // remove unneccesary variables from oData 
    delete oData.append;
    delete oData.callback;
    
    this.each(function() { 
        var thisContainer = this;
        
                if(!o.append) {
                    var waitingcode = "<div class='reqWaiting'><div>";
                    $(thisContainer).html(waitingcode);
                }

                $.ajax({
                  url: "/req/",
                  data: oData,
                  success: function( data ) {

                    // JSON RESPONSE -> INDICATES ERROR MESSAGE
                    try {
                        var jr = $.parseJSON(data);
                        if(jr.error){
                            try{
                                if(jr.error == -9801){
                                    window.location.replace("/app/xMain/login");
                                }
                                else if(jr.error==-9802){
                                    window.location.replace(jr.description);
                                }
                                else{
                                    displayErrorMsg(jr.error, jr.description, jr.details);
                                }
                            }
                            catch (err){
                                alert("ERROR: " + jr.   error + "\n\n" + jr.description + "\n\n" + jr.details);
                            }
                        }
                    }
                    catch (err) {
                        // NO JSON RESPONSE -> RESPONSE FROM MODULE TO BE DISPLAYED
                        if(o.append){
                            $( thisContainer ).append(data);
                        }
                        else{
                            //$( this ).hide().html(data).fadeIn(200);
                            $( thisContainer ).html(data);
                        }
                        if( jQuery.isFunction(o.callback) ){
                            o.callback(data);
                        }

                    }

                  }
                });
        
    });


}

function executeRequest2(reqModule, reqCommand, options){
    
    var execDefaults = {
        module:  reqModule,
        command: reqCommand,
        callback: function(){}
    }
    
    var o = $.extend(o, execDefaults, options);    
    
    $.ajax({
      url: "/req/",
      data: o,
      success: function( data ) {
          if( jQuery.isFunction(o.callback)){
            o.callback(data);
          }
      }
    });
}

function navigate(u){
    window.location = u;
}


var vRequestData = null;

function setVishnuRequestVars(module, command, data1, data2, data3){
    cbc2RequestData = {
        module: module,
        command: command,
        data1: data1,
        data2: data2,
        data3: data3
    };
}

function getVishnuRequestUrl(includeVars = 3){
    var thisUrl = '/app/' + cbc2RequestData.module + '/';
    if(cbc2RequestData.command) thisUrl = thisUrl + '/' + cbc2RequestData.command;
    if(includeVars >= 1 && includeVars.data1) thisUrl = thisUrl + '/' + cbc2RequestData.data1;
    if(includeVars >= 2 && includeVars.data2) thisUrl = thisUrl + '/' + cbc2RequestData.data2;
    if(includeVars >= 3 && includeVars.data3) thisUrl = thisUrl + '/' + cbc2RequestData.data3;
}
