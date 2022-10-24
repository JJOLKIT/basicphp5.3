<?
	include_once $_SERVER['DOCUMENT_ROOT']."/lib/siteProperty.php";
?>
<!doctype html>
<html lang="ko">
<head>
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<title>:SUBJECT</title>    
<meta http-equiv='Content-Type' content='text/html; charset=utf-8'>
<link rel='stylesheet' href='<%=SiteProperty.COMPANY_URL%>/css/reset.css' />
</head>
<body leftmargin='0' topmargin='0'>
<table border='0' cellpadding='0' cellspacing='0' width='700' align='center' style='width:700px; margin:0 auto;'>
<tr>
	<td>
		<table border='0' cellpadding='0' cellspacing='0' bgcolor='#f9f9f9' style='background:#054d81; border-top:2px solid #031b33;'>
					<tr>
					<td>
						<table border='0' cellpadding='0' cellspacing='0' bgcolor='#FFFFFF' style='padding:20px 0 0; background:#f9f9f9;'>
						<tr>
							<td style='padding-top:60px; font-size: 40px; color: #031b33; line-height: 1; text-align: center;'>
								<b><?=COMPANY_NAME?></b>
							</td>
						</tr>

						<tr>
							<td style='padding:0 70px 60px 70px; font-size:16px; color: #424242; text-align: center;'>
										:CONTENT					
							</td>
						</tr>
						<tr>
							<td bgcolor='#031b33' style='padding: 40px 70px; font-size:14px; background:#333;'>
									<b style='color:#fff; font-size: 18px;'><?=COMPANY_NAME?></b><br><br>
									<p style='font-size:13px; line-height:20px; color:#fff;'>
										08506 서울시 금천구 가산디지털1로 131, C동 1502호 (BYC하이시티, 가산동)<br>
										<b style='color:#df1b51;'>T.</b> 070-7860-4306  
									</p>
							</td>
						</tr>
						</table>
					</td>
				</tr>
		</table>
	</td>
</tr>
</table>
</body>
</html>