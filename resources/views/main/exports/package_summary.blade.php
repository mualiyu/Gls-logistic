<table>
    <thead>
    <tr>
        <th colspan="10" style="text-align: center; height:25px; font-weight:bold; font-size:16px;"></th>
    </tr>
    <tr >
        <th style="text-align: center; height:17px; width:20%; font-size:14px;">N° Commande</th>
        <th style="text-align: center; height:17px; width:20%; font-size:14px;">Customer/ CLIENT</th>
        <th style="text-align: center; height:17px; width:20%; font-size:14px;">Depart</th>
	<th style="text-align: center; height:17px; width:20%; font-size:14px;">Destination</th>
        <th style="text-align: center; height:17px; width:20%; font-size:14px;">Adresse Client</th>
	<th style="text-align: center; height:17px; width:20%; font-size:14px;">Tracking No</th>
        <th style="text-align: center; height:17px; width:20%; font-size:14px;">Cout Transport</th>
	<th style="text-align: center; height:17px; width:20%; font-size:14px;">Contact Client</th>
        <th style="text-align: center; height:17px; width:20%; font-size:14px;">Email Client</th>
	<th style="text-align: center; height:17px; width:20%; font-size:14px;">N° FACTURE</th>
	<th style="text-align: center; height:17px; width:20%; font-size:14px;">Delivery Image URL</th>
    </tr>
    </thead>
    <tbody>
        <?php $j = 1; ?>
    @foreach($packages as $p)
        <tr>
            <td>{{ $j }}</td>
            <td>{{ $p->customer->name }}/</td>
            <td>{{ $p->from }}</td>
	    <td>{{ $p->to }}</td>
            <td>{{ $p->address_to }}</td>
	    <td>{{ $p->tracking_id }}</td>
            <td>{{ $p->total_amount }}</td>
	    <td>{{ $p->phone }}</td>
            <td>{{ $p->email }}</td>
	    <td>{{ $p->c_way_bill }}</td>
            <td>{{ $p->delivery_image }}</td>
	    {{-- <td>{{ $p->from }}</td>
            <td>{{ $p->to }}</td> --}}
        </tr>
        <?php $j++; ?>
    @endforeach
    </tbody>
</table>
