<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional //EN">
<html><head><meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <title>Mạng sự kiện, hội thảo thamgia.net</title>
    </head>
    <body style="margin: 0; padding: 0;" dir="ltr">
        <table width="98%" border="0" cellspacing="0" cellpadding="40">
            <tr>
                <td bgcolor="#f7f7f7" width="100%" style="font-family: 'lucida grande', tahoma, verdana, arial, sans-serif;">
                    <table cellpadding="0" cellspacing="0" border="0" width="620">
                        <tr>
                            <td style="background: #045FB4; color: #fff; font-weight: bold; font-family: 'lucida grande', tahoma, verdana, arial, sans-serif; padding: 4px 8px; vertical-align: middle; font-size: 16px; letter-spacing: -0.03em; text-align: left;"><a href="http://thamgia.net" style="text-decoration:none; color:#fff">thamgia.net</a></td>
                        </tr>
                        <tr>
                            <td style="background-color: #fff; border-bottom: 1px solid #3b5998; border-left: 1px solid #ccc; border-right: 1px solid #ccc;font-family: 'lucida grande', tahoma, verdana, arial, sans-serif; padding: 15px;" valign="top">
                                <table>
                                    <tr>
                                        <td width="470px" style="font-size: 12px;" valign="top" align="left">
                                            <div style="margin-bottom: 15px; font-size: 13px;">Xin chào <?php echo $owner_name; ?>,</a></div>
                                            <div style="margin-bottom: 15px;">Có một thành viên vừa đăng ký tham gia sự kiện "<?php echo $event_title; ?>" của bạn. Bạn có thể xem thông tin thành viên tham gia hoặc đăng nhập vào khu vực quản lý sự kiện.</div>
                                            <div style="margin-bottom: 15px;">
                        
                                                <table>
                                                    <tr></tr>
                                                    <tr>
                                                        <td valign="top" style="padding-right: 15px;">

                                                        </td>
                                                        <td valign="top" style="font-size: 12px; padding: 3px 0 15px 0;">
                                                            <div>
                                                                <a href="<?php echo  Router::url(array('controller' => 'Users', 'action' => 'profile', $user_id), true ); ?>" style="color: #3b5998; text-decoration: none; display: block; font-weight: bold;"><?php echo $user_name;  ?></a>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                </table>
                                                <div>Nếu bạn có thắc mắc cần giải đáp, xin vui lòng tra cứu mục <a href="<?php echo  Router::url(array('controller' => 'Home', 'action' => 'faq'), true ); ?>" style="color: #3b5998; text-decoration: none;">Hỏi đáp</a> hoặc <a href="<?php echo  Router::url(array('controller' => 'Home', 'action' => 'contact'), true ); ?>" style="color: #3b5998; text-decoration: none;">Liên hệ với chúng tôi</a>.</div>
                                            </div>
                                            <div style="margin-bottom: 15px; margin: 0;">Trân trọng cảm ơn,<br />
Nhóm phát triển thamgia.net</div>
                                        </td>
                                    <td valign="top" width="150" style="padding-left: 15px;" align="left">
                                        <table width="100%" cellspacing="0" cellpadding="0">
                                            <tr>
                                                <td style="background-color: #FFF8CC; border: 1px solid #FFE222; color: #333; padding: 15px; font-size: 12px;">
                                                        <table cellspacing="0" cellpadding="0">
                                                            <tr>
                                                                <td style="border: 1px solid #3b6e22;">
                                                                    <table cellspacing="0" cellpadding="0">
                                                                        <tr>
                                                                            <td style="padding: 5px 5px;background-color: #029F1C;border-top: 1px solid #95bf82;"><a href="<?php echo  Router::url(array('controller' => 'Events', 'action' => 'addedEvents'), true ); ?>" style="color: #fff;font-size: 12px;font-weight: bold;text-decoration: none;">Quản lý sự kiện</a>
</td>
</tr></table></td></tr></table></td></tr><tr><td></td></tr></table></td></tr></table></td></tr><tr><td style="color: #999; padding: 10px; font-size: 11px; font-family: 'lucida grande', tahoma, verdana, arial, sans-serif;">Bạn nhận được email này vì bạn đã đăng ký vào hệ thống mail của chúng tôi để nhận các thông tin cập nhật. Nếu bạn không muốn nhận thông báo này, hãy <a href="<?php echo  Router::url(array('controller' => 'Events', 'action' => 'contact'), true ); ?>" style="color: #999">Liên hệ gỡ bỏ đăng ký</a>. Nếu bạn có nhu cầu về giúp đỡ xin vui lòng gửi mail về địa chỉ info@thamgia.net.<br>Bản quyền đã được đăng ký © 2012 thamgia.net</td></tr></table></td></tr></table></body></html>