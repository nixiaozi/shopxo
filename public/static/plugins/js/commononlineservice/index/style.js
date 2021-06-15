$(function()
{
  // 在线客服
  $('.commononlineservice .btn-open').click(function()
    {
        $('.commononlineservice .content').toggle(300);
        $('.commononlineservice .btn-open').css('display', 'none');
        $('.commononlineservice .btn-ctn').css('display', 'block');        
    });

    $('.commononlineservice .btn-ctn').click(function()
    {
        $('.commononlineservice .content').toggle(300);
        $('.commononlineservice .btn-open').css('display', 'block');
        $('.commononlineservice .btn-ctn').css('display', 'none');
    });
});