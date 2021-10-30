<table>
    <thead>
    <tr>
        <th colspan="10" style="text-align: center; height:25px; font-weight:bold; font-size:16px;" >Package Summary Report for {{$packages[0]->customer->name}}</th>
    </tr>
    <tr >
        <th style="text-align: center; height:17px; width:20%; font-size:14px;">Customer</th>
        <th style="text-align: center; height:17px; width:20%; font-size:14px;">Depart</th>
	<th style="text-align: center; height:17px; width:20%; font-size:14px;">Arrivee</th>
        <th style="text-align: center; height:17px; width:20%; font-size:14px;">Arrivee Address</th>
	<th style="text-align: center; height:17px; width:20%; font-size:14px;">Tracking No</th>
        <th style="text-align: center; height:17px; width:20%; font-size:14px;">Amount</th>
	<th style="text-align: center; height:17px; width:20%; font-size:14px;">Contact Phone</th>
        <th style="text-align: center; height:17px; width:20%; font-size:14px;">Contact Email</th>
	<th style="text-align: center; height:17px; width:20%; font-size:14px;">Way bill</th>
	<th style="text-align: center; height:17px; width:20%; font-size:14px;">Delivery Image URL</th>
    </tr>
    </thead>
    <tbody>
    @foreach($packages as $p)
        <tr>
            <td>{{ $p->customer->name }}</td>
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
    @endforeach
    </tbody>
</table>
