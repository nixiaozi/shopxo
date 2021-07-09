$(function(){
    // common.js 就是插件全局的js。
    console.log("正在执行 gold_coin 插件全局的JS!");
    

});

function SunlitAjax(element,beforeAction=null,completeAction=null)
{
    // 参数
	var id = element.attr('data-id');
	var key = element.attr('data-key') || 'id';
	var field = element.attr('data-field') || '';
	var value = element.attr('data-value') || '';
	var url = element.attr('data-url');
	var view = element.attr('data-view') || '';

	// 请求数据
	var data = {"value": value, "field": field};
		data[key] = id;

    var $button = $(element);

    $.ajax({
		url:url,
		type:'POST',
		dataType:"json",
		timeout:element.attr('data-timeout') || 60000,
		data:data,
        beforeSend:function(){
			if(beforeAction==null){
				$button.button("loading");
			}else{
				beforeAction();
			}
        },
		success:function(result)
		{
			if(result.code == 0)
			{
				switch(view)
				{
					// 成功则删除数据列表
					case 'delete' :
						Prompt(result.msg, 'success');
						$('#data-list-'+id).remove();
						break;

					// 刷新
					case 'reload' :
						Prompt(result.msg, 'success');
						setTimeout(function()
						{
							window.location.reload();
						}, 1500);
						break;

					// 回调函数
					case 'fun' :
						if(IsExitsFunction(value))
                		{
                			window[value](result);
                		} else {
                			Prompt('['+value+']配置方法未定义');
                		}
						break;

					// 默认提示成功
					default :
						Prompt(result.msg, 'success');
				}
			} else {
				Prompt(result.msg);
			}
		},
		error:function(xhr, type)
		{
			Prompt(HtmlToString(xhr.responseText) || '异常错误', null, 30);
		},
        complete:function()
        {
			if(completeAction==null){
				$button.button("reset");
			}else{
				completeAction();
			}
        }
	});

}


function TimeoutPromise( action,delay=0){
	return new Promise(function (resolve, reject) {
        setTimeout(function () {
			if(action instanceof Function){
				action();
			}
            resolve();
        }, delay);
    });

}

// function PromiseFunc(doAction,successAction=null,errorAction=null,finialAction=null)
// {
// 	return new Promise(function(resolve,reject){
// 		if(doAction instanceof Function){
// 			var result = doAction();
// 			if(result==='undefined'){
// 				resolve();
// 			}else{
// 				resolve(result);
// 			}
// 		}else{
// 			resolve();
// 		}

// 	}).then(result=>function(result){
// 		if(successAction instanceof Function){
// 			successAction();
// 		}
// 	}).catch(err=>function(err){
// 		if(errorAction instanceof Function){
// 			errorAction();
// 		}else{
// 			throw err; // 直接抛出异常
// 		}
// 	}).finally(function(){
// 		if(finialAction instanceof Function){
// 			finialAction();
// 		}
// 	});
// }
/**  以上注释的方法并没有什么意义，不如直接使用Promise ; 只有执行多次同类参数的异步方法时才会有些意义 */




