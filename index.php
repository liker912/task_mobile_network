<!doctype html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/vue/dist/vue.js"></script>
    <title>Hello, world!</title>
</head>
<body>
<div id="app">
 <div class="container mt-5">
     <h1 class="mb-2">iCount Israel</h1>
     <div class="row justify-align-center">
         <div class="col-4">
             <select name="countries" class="form-control" @change="onChangeCountry($event)">
                 <option value="" selected disabled>Choose country</option>
                 <option v-for="(country, index) in countries" :key="country.id" :value="index">{{country.country_name}}</option>
             </select>
         </div>
         <div class="col-4">
             <select name="operators" class="form-control" @change="onChangeOperator($event)">
                 <option value="" selected disabled>Choose operator</option>
                 <option v-for="(operator, index) in operators" :key="operator.id" :value="index">{{operator.operator}}</option>
             </select>
         </div>
     </div>
     <div class="row justify-align-center mt-5" v-if="selectedOperator && selectedCountry">
         <table class="table table-striped">
             <thead>
             <tr>
                 <th scope="col">Country</th>
                 <th scope="col">Code</th>
                 <th scope="col">MCC</th>
                 <th scope="col">MNC</th>
                 <th scope="col">Brand</th>
                 <th scope="col">Operator</th>
                 <th scope="col">bands</th>
             </tr>
             </thead>
             <tbody>
             <tr>
                 <td>{{selectedCountry.country_name}}</td>
                 <td>{{selectedCountry.code}}</td>
                 <td>{{selectedOperator.mcc}}</td>
                 <td>{{selectedOperator.mnc}}</td>
                 <td>{{selectedOperator.brand}}</td>
                 <td>{{selectedOperator.operator}}</td>
                 <td>{{selectedOperator.bands}}</td>
             </tr>
             </tbody>
         </table>
     </div>
 </div>
</div>

<!-- Optional JavaScript -->
<!-- jQuery first, then Popper.js, then Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js" integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous"></script>
<script src="/src/main.js"></script>
</body>
