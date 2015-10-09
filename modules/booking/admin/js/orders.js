function resend_email(id)
{
    try
    {
        if (confirm("Are you sure?"))
        {
            var jsel = document.createElement('SCRIPT');
            jsel.type = 'text/javascript';
            jsel.src = '/modules/shop/admin/ajax.php?action=resend_email&id='+id;
            document.body.appendChild (jsel);        
        }
    } 
    finally 
    {
        return false;
    }
}
