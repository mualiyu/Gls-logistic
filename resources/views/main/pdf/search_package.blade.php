<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" integrity="sha384-B0vP5xmATw1+K9KRQjQERJvTumQW0nPEzvF6L/Z6nronJ3oUOFUFpCjEUQouq2+l" crossorigin="anonymous">
	<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-Piv4xVNRyMGpqkS2by6br4gNJ7DXjqk09RmUpJ8jgGtD7zP9yug3goQfGII0yAns" crossorigin="anonymous"></script>
	<title>Gls</title>
	<style>
		html { margin: 30px 0px 10px 0px}
		#table thead tr th{
			border: black 1px solid;
		}
		#table tbody tr td{
			border: black 1px solid;
			padding: 0px 10px 0px 10px;
		}
		#table tr td{
			border: white 1px solid;
		}
		.card-body{
			/* border: black 1px solid; */
		}
	</style>
</head>
<body>
<div class="container-fluid px-3 py-3" style="text-align: center;">
	<div class="row" style="justify-content: ">
		<div class="col-sm-2" style="float:left">
				{{-- <img style="float:; width: 100px; height:100px; margin:15px;" src="{{asset('main2/img/core-img/logo.png')}}" alt=""> --}}
			
		</div>
		<div class="colsm-10" style="margin-left: 35px;">
				<h2 style="text-decoration: underline; color:rgb(15,53,125);">GLOBE LINE SERVICES SARL</h2>
				<P>COMEMERCE GENERAL - NEGOCE - TRANSPORT - SERVICES</P>
		</div>
	</div>
	<br><br>
	<br><br>
	<div class="row px-3 py-3" style="text-align: left;">
		<div class="col">
			<div class="row">
				<div class="col-sm-6" style="float: left;">
					<h6>FACTURE N: FA003005</h6>
					<p style="margin: 0;">R.C: N B/361 </p>
					<p style="margin: 0;">NB: 1 Tonne = 3 metre cube</p>
					<br>
					<p>condition de palement : 30 jour date depot facture</p>
				</div>
				<div class="colsm-6">
					<p style="margin: 0;">Duala, le {{$from}}</p>
					<div class="card">
						<div class="card-body" style=" font-size:13px;">
							<p style="margin: 0;"><b>ORANGE CAMEROUN SA</b></p>
							<p style="margin: 0;"><b>BP.: 18230 DOUALA</b></p>
							<p style="margin: 0;"><b>CAMEROUN</b></p>
							<p style="margin: 0;"><b>NIU: M059900009243M</b></p>
							<p style="margin: 0;"><b>RCCM: DLA/2002/B/027585</b></p>
						</div>
					</div>
				</div>
			</div>
	
			<div class="row" style="font-size: 11px;">
				<table class="table border-dark" style="padding: 0;" id="table">
					<thead>
						<tr>
							<th style="border: black 1px solid;" colspan="7">MATERIEL</th>
							<th colspan="3">DATE EXPENDITION</th>
						</tr>
						<tr>
							<th>Depart</th>
							<th>Arrivee</th>
							<th>N B.L</th>
							<th>B.L ORG</th>
							<th>Nature </th>
							<th>Poids</th>
							<th>Prix. U </th>
							<th>Depart</th>
							<th>Arrivee </th>
							<th>Cout HT</th>
						</tr>
					</thead>
					<tbody>
						@foreach ($packages as $p)
						<tr>
							<td>{{$p->from}}</td>
							<td>{{$p->to}}</td>
							<td>{{$p->tracking_id}}</td>
							<td> --- </td>
							<td>{{$p->items[0]->description ?? "No Description"}}</td>
							<td>{{$p->items[0]->weight ?? "No Weight"}}KG</td>
							<td>{{$p->total_amount}}</td>
							<td>{{$p->trackings[0]->created_at ?? '---'}}</td>
							<td>{{$p->trackings[count($p->trackings)-1]->created_at ?? "---"}}</td>
							<td>{{$p->total_amount}}</td>
						</tr>
						@endforeach
							
					</tbody>
					<hr>
						
					
				</table>
				
			</div>
			<div class="row" style="font-size: 12px;">
				<?php
				$t_amount = 0;
				
				foreach ($packages as $p) {
					$t_amount = $t_amount + $p->total_amount;
				}
					?>
				<table class="table border-dark" style="padding: 0;" id="table">
					<tr style=" font-weight:bold">
							<td style="padding:0; border:  1px solid;" colspan="8">
							Arrete la presente facture au prix total de FCFA : 
							</td>
							<td style="padding:0;border:  1px solid;">TOTAL</td>
							<td style="padding:0;border: black 1px solid !important;">{{$t_amount}}</td>
						</tr>
						<tr style=" font-weight:bold">
							<td style="padding:0;border:  1px solid;" colspan="8"> Trios millions duex cent quatre vingt onze mille trois cent</td>
							<td style="padding:0;border:  1px solid;">REMISE</td>
							<td style="padding:0;border: black 1px solid !important;">0</td>
						</tr>
						<tr style=" font-weight:bold">
							<td style="padding:0;border:  1px solid;" colspan="8"></td>
							<td style="padding:0;border:white  1px solid;">TOTAL HTY</td>
							<td style="padding:0; border-left: black 1px solid; border: black 1px solid !important;">0</td>
						</tr>
	
						<!-- schd -->
						<tr style=" font-weight:bold">
							<td style="padding:0;border: black 1px solid;" colspan="5">
							Information relatives au paiement <br>
							Nom & Address de la banque : BACEC BANANJO <br>
							N de compete : 10001 - 06800 - 67135618937 -39  
							</td>
							<td style="padding:0;border:  1px solid;" colspan="3"></td>
							<td style="padding:0;border:  1px solid;">TVA 19,253</td>
							<td style="padding:0;border: black 1px solid !important;">0</td>
						</tr>
						<!-- hfjf -->
						<tr style=" font-weight:bold">
							<td style="padding:0;border:  1px solid;" colspan="8"></td>
							<td style="padding:0;border:  1px solid;">TOTAL TTC</td>
							<td style="padding:0;border: black 1px solid !important;">{{$t_amount}}</td>
						</tr>
				</table>
			</div>
			<br><br>

			<div class="row" style="text-align: center;">
				<div class="col"></div>
				<div class="col">
					<p>LA DIRECTION</p>
				</div>
				<div class="col"></div>
			</div>
		</div>
	</div>

	<div class="row" style="font-size: 12px; bottom:0px; ">
		<div class="col justify-content-center" style="background-color: aqua;">
			<div>
				<!-- <h2 style="text-decoration: underline;">GLOBE LINE SERVICES SARL</h2> -->
				<P style="margin:0;">Akwa, Rue Jamot-Doula BP:2409 Doula - Cameroun Tel:(237) 696987131 - 677822819 - 699505956</P>
				<p style="margin:0;">Email: glscameroun@yahoo.fr - RC No B/361 - NIU : M030600020460-E - No EMP : 351-0106190-W</p>
			</div>
		</div>
	</div>
</div>	
</body>
</html>
