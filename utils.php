<?php
function printHeader($title = 'Вега - МИРЭА', $submenu = "", $styles = NULL, $scripts = NULL)
{
?>
<html>
    <head> 
        <title><?=$title?></title>
        <meta name="keywords" content="базовая кафедра, программное обеспечение, программные средства, прикладная математика, кибернетика, радиоэлектронная аппаратура, ОАО Концерн радиостроения, Вега, Московский институт радиотехники электроники автоматики, МИРЭА, МГИРЭА, абитуриенты, студенты, аспиранты, преподаватели">
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"> 
<?php
    if(is_array($styles))
    {
        foreach($styles as $style)
        {
?>
        <link href="<?=$style?>" type="text/css" rel="stylesheet">
<?php
        }
    }

    if(is_array($scripts))
    {
        foreach($scripts as $script)
        {
?>
        <script type="text/javascript" src="<?=$script?>"></script>
<?php
        }
    }
?>
    </head>
    <body bgcolor="#CCCCCC" bottommargin=0 topmargin=0 leftmargin=0 rightmargin=0>
        <table width="100%" cellspacing=0 cellpadding=0 border=0 height="100%">
            <tr>
                <td width="50%" bgcolor="#CCCCCC" rowspan=7></td>
                <td bgcolor="#CCCCCC"></td>
                <td colspan=2 bgcolor="#FFFFFF"><img src="../images/pixel.gif" height=10></td>
                <td bgcolor="#CCCCCC"></td>
                <td width="50%" bgcolor="#CCCCCC" rowspan=7></td>
            </tr>
            <tr>                                                                                                               
                <td width=531 height=164 colspan=2 style="background-image: url(../images/h-c.png);"><a href="/index.php"><img src="../images/h-l.png" width=531 border=0></a></td> 
                <td width=531 rowspan=2 colspan=2 style="background-image: url(../images/h-c.png);" align="right"><img src="../images/h-r.png" width=531></td>
            </tr>
            <tr>
                <td bgcolor="#cccccc">&nbsp;</td> 
                <td bgcolor="#ffffff" height=70 valign=middle style="{font-family:Arial Narrow,Trebuchet MS,Arial,Verdana,Tahoma;}">
                    <div style="{margin-left: 15px; font-size:12pt; color:#4C627A;}">
                        <a href="/about.php">О кафедре</a> | <a href="/appmath.php">О прикладной математике</a> | <a href="/disc.php">Дисциплины</a> | <a href="/vega.php">О Концерне</a>
                        </div>
                        <?=$submenu?>
                </td> 
            </tr>
            <tr>
                <td bgcolor="#cccccc" width=21 rowspan="2"><img src="../images/pixel.gif" width=21 height=1></td>
                <td bgcolor="#ffffff" width=510><img src="../images/pixel.gif" width=510 height=1></td>
                <td bgcolor="#ffffff" width=515><img src="../images/pixel.gif" width=515 height=1></td>
                <td bgcolor="#cccccc" width=16 rowspan="2"><img src="../images/pixel.gif" width=16 height=1></td>
            </tr>
            <tr> 
                <td bgcolor="#ffffff" width=1025 colspan=2 height="100%" valign=top>
                    <table cellpadding=10 width="100%" height="100%" border=0>
                        <tr>
                            <td width=1025 valign=top style="{font-family:Arial Narrow,Trebuchet MS,Arial,Verdana,Tahoma; font-size:12pt; width: 1025px !important;}">
<?php
}

function printFooter()
{
?>
                            </td>
                        </tr>
                    </table>
                </td> 
                <!---<td bgcolor="#cccccc"></td>--->
            </tr>
            <tr> 
                <td colspan=4 width=1062><a href="mailto:lebedeva.sg@yandex.ru"><img border=0 src="../images/f.png" width=1062></a></td>
            </tr>
            <tr>
                <td bgcolor="#cccccc" width=21><img src="../images/pixel.gif" width=21 height=1></td>
                <td colspan=2 width=1025 bgcolor="#FFFFFF">
                    <table width="100%" align="center" cellspacing=10 cellpadding=0>
                        <tr> 
                            <td>
                                <!-- HotLog --> <script language="javascript"> hotlog_js="1.0"; hotlog_r=""+Math.random()+"&s=270979&im=134&r="+escape(document.referrer)+"&pg="+ escape(window.location.href); document.cookie="hotlog=1; path=/"; hotlog_r+="&c="+(document.cookie?"Y":"N"); </script>
                                <script language="javascript1.1"> hotlog_js="1.1";hotlog_r+="&j="+(navigator.javaEnabled()?"Y":"N")</script> 
                                <script language="javascript1.2"> hotlog_js="1.2"; hotlog_r+="&wh="+screen.width+'x'+screen.height+"&px="+ (((navigator.appName.substring(0,3)=="Mic"))? screen.colorDepth:screen.pixelDepth)</script> 
                                <script language="javascript1.3"> hotlog_js="1.3"</script> 
                                <script language="javascript">hotlog_r+="&js="+hotlog_js; document.write("<a href='http://click.hotlog.ru/?270979' target='_top'><img " + " src='http://hit10.hotlog.ru/cgi-bin/hotlog/count?"+ hotlog_r+"&' border=0 width=88 height=31 alt=HotLog></a>")</script> 
                                <noscript><a href="http://click.hotlog.ru/?270979" target=_top><img src="http://hit10.hotlog.ru/cgi-bin/hotlog/count?s=270979&im=134" border=0  width="88" height="31" alt="HotLog"></a></noscript> <!-- /HotLog --> 
                            </td> 
                            <td width="100%"> 
                                <div align="center" style="{font-family:Arial Narrow,Trebuchet MS,Arial,Verdana,Tahoma; font-size:10pt;}">МОСКВА <?=date('Y', time())?></div> 
                            </td> 
                            <td> 
                                <!--Rating@Mail.ru COUNTER--><script language="JavaScript" type="text/javascript"><!-- 
                                 d=document;var a='';a+=';r='+escape(d.referrer); 
                                 js=10//--></script><script language="JavaScript1.1" type="text/javascript"><!-- 
                                 a+=';j='+navigator.javaEnabled(); 
                                 js=11//--></script><script language="JavaScript1.2" type="text/javascript"><!-- 
                                 s=screen;a+=';s='+s.width+'*'+s.height; 
                                 a+=';d='+(s.colorDepth?s.colorDepth:s.pixelDepth); 
                                 js=12//--></script><script language="JavaScript1.3" type="text/javascript"><!-- 
                                 js=13//--></script><script language="JavaScript" type="text/javascript"><!-- 
                                 d.write('<a href=\"http://top.mail.ru/jump?from=916242\"'+ 
                                 ' target=_top><img src=\"http://top.list.ru/counter'+ 
                                 '?id=916242;t=216;js='+js+a+';rand='+Math.random()+ 
                                 '\" alt=\"Рейтинг@Mail.ru\"'+' border=0 height=31 width=88/><\/a>'); 
                                 if(11<js)d.write('<'+'!-- ')//--></script><noscript><a 
                                 target=_top href="http://top.mail.ru/jump?from=916242"><img 
                                 src="http://top.list.ru/counter?js=na;id=916242;t=216" 
                                 border=0 height=31 width=88 
                                 alt="Рейтинг@Mail.ru"/></a></noscript><script language="JavaScript" type="text/javascript"><!-- 
                                 if(11<js)d.write('--'+'>')//--></script><!--/COUNTER--> 

                            </td> 
                        </tr> 
                    </table>
                </td>
                <td bgcolor="#cccccc" width=16><img src="../images/pixel.gif" width=16 height=1></td>
            </tr>
        </table>
    </body>
</html>
<?php
}

function vHeader()
{
    printHeader();
}

function vutilster()
{
    printFooter();
}
?>