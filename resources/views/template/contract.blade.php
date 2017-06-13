<!DOCTYPE html>
<html>
<head>
	<title></title>

	<style type="text/css">
		body {
			color: #000033;
			font-family: "verdana", "sans-serif";
			margin: 0px;
			padding-top: 0px;
			font-size: 0.8em;
		}

		.colHead {
			font-weight: bold; 
			padding-top: 15px;
		}

		   /* Footer
    -----------------------------------------------------------------------*/
    #footer {
      color: #FFFFff;
      border-top: 1px solid #000033;
    }

    #copyright { 
      padding: 5px;
      font-size: 0.6em;
      background-color: #114C8D;
    }

    #footer_spacer_row {
      border-spacing: 0;
      width: 100%;
    }
    .footertwo { 
      position: fixed; 
      left: 0px; bottom: -150px; right: 0px; height: 150px; 
    }

    #footer_spacer_row td {
      padding: 0px;
      border-bottom: 1px solid #000033;
      background-color: #F7CF07;
      height: 2px;
      font-size: 2px;
      line-height: 2px;
    }

	</style>
</head>
<body>
    <table style="width: 100%;">
        <tbody>
            <tr>
                <td style="width: 500px;"><img src="{{ base_path().'images/logo.png' }}" width="150" /></td>
            </tr>
        </tbody>
    </table>

	{!! isset($contentBlock[45]) ? $contentBlock[45] : '' !!}

	<table>
		<tr>
			<td></td>
		</tr>
		<tr>
			<td colspan="2" class="colHead">Bankgegevens t.b.v. automatisch incasso:</td>
		</tr>
		<tr>
			<td style="width: 300px;">IBAN:</td>
			<td style="border-bottom: 1px dotted #000;">{{ $contactIBAN }}</td>
		</tr>
		<tr>
			<td style="width: 300px;">Ter name van:</td>
			<td style="border-bottom: 1px dotted #000;">{{ $contactIBAName }}</td>
		</tr>
		<tr>
			<td colspan="2" class="colHead">Voor akkoord automatisch incasso:</td>
		</tr>
		<tr>
			<td style="width: 300px;">Datum:</td>
			<td style="border-bottom: 1px dotted #000;">{{ $companyDate }}</td>
		</tr>
		<tr>
			<td style="width: 300px;">Bedrijfsnaam:</td>
			<td style="border-bottom: 1px dotted #000;">{{ $companyName }}</td>
		</tr>
		<tr>
			<td style="width: 300px;">Naam tekenbevoegd persoon:</td>
			<td style="border-bottom: 1px dotted #000;">{{ $contactName }}</td>
		</tr>
		<tr>
			<td style="width: 300px;">Functie:</td>
			<td style="border-bottom: 1px dotted #000;">{{ $contactRole }}</td>
		</tr>
		<tr>
			<td style="width: 300px;">Handtekening:</td>
			<td>
				@if ($companySignature)
				<img  style="width: 200px;" src="data:{{ $companySignature }}">
				@endif
			</td>
		</tr>
		<tr>
			<td colspan="2" class="colHead">Websitegegevens:</td>
		</tr>
		<tr>
			<td style="width: 300px;">KVK:</td>
			<td style="border-bottom: 1px dotted #000;">{{ $companyKVK }}</td>
		</tr>
		<tr>
			<td style="width: 300px;">BTW:</td>
			<td style="border-bottom: 1px dotted #000;">{{ $companyBTW }}</td>
		</tr>
		<tr>
			<td style="width: 300px;">Adres:</td>
			<td style="border-bottom: 1px dotted #000;">{{ $companyAddress }}</td>
		</tr>
		<tr>
			<td style="width: 300px;">Postcode / plaats:</td>
			<td style="border-bottom: 1px dotted #000;">{{ $companyZipcode.' '.$companyCity }}</td>
		</tr>
		<tr>
			<td style="width: 300px;">E-mail reserveringen:</td>
			<td style="border-bottom: 1px dotted #000;">{{ $companyEmailReservation }}</td>
		</tr>
		<tr>
			<td style="width: 300px;">E-mail administratie:</td>
			<td style="border-bottom: 1px dotted #000;">{{ $companyEmailAdmin }}</td>
		</tr>
		<tr>
			<td style="width: 300px;">Mobiel telefoonnummer:</td>
			<td style="border-bottom: 1px dotted #000;">{{ $contactMobile }}</td>
		</tr>
		<tr>
			<td style="width: 300px;">Vast telefoonnummer:</td>
			<td style="border-bottom: 1px dotted #000;">{{ $contactPhone }}</td>
		</tr>
	</table>
   	
	<div class="footertwo">
   		{!! isset($contentBlock[46]) ? $contentBlock[46] : '' !!}
    </div>
    
   	<div style="page-break-before:always">&nbsp;</div> 

		<h4>Algemene Voorwaarden</h4>
		{!! isset($contentBlock[43]) ? $contentBlock[43].'<br /><br />' : '' !!}

		<table>
			<tr>
				<td><span style="border-bottom: 1px dotted #000; padding-right: 20px; font-size: 16px;">{{ $contactName }}</span></td>
				<td><span style="border-bottom: 1px dotted #000; padding-right: 20px; font-size: 16px;">{{ $companyDate }}</span></td>
				<td>
					@if ($companySignature)
					<span style="border-bottom: 1px dotted #000; padding-right: 20px; font-size: 16px;"><img style="width: 200px;" src="data:{{ $companySignature }}"></span>
					@endif
				</td>
			</tr>
			<tr>
				<td style="width: 250px; font-weight: bold;">Naam contactpersoon:</td>
				<td style="width: 250px; font-weight: bold;">Datum:</td>
				<td style="width: 25px; font-weight: bold;">Handtekening:</td>
			</tr>
		</table>

	<div class="footertwo">
   		{!! isset($contentBlock[46]) ? $contentBlock[46] : '' !!}
    </div>
</body>
</html>