var app = new Vue({
    el: '#app',
    data: {
        countries: [],
        operators: [],
        selectedOperator: null,
        selectedCountry: null
    },
    methods: {
        getCountries() {
            const xmlHttp = new XMLHttpRequest();
            xmlHttp.open("GET", '/api.php', false); // false for synchronous request
            xmlHttp.send(null);
            this.countries = JSON.parse(xmlHttp.responseText);
        },

        getOperators(idx) {
            const xmlHttp = new XMLHttpRequest();
            const params = 'idx=' + idx;
            const self = this;
            xmlHttp.open('POST', '/api.php', true);
            xmlHttp.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
            xmlHttp.onreadystatechange = () => {//Call a function when the state changes.
                if (xmlHttp.readyState == 4 && xmlHttp.status == 200) {
                    self.operators = JSON.parse(xmlHttp.responseText);
                    if (self.selectedOperator) {
                        self.selectedOperator = self.operators[0];
                    }
                }
            };
            xmlHttp.send(params)
        },

        onChangeCountry(e) {
            this.selectedCountry = this.countries[e.target.value];
            this.getOperators(this.countries[e.target.value].idx)
        },

        onChangeOperator(e) {
            this.selectedOperator = this.operators[e.target.value]
        }
    },

    beforeMount() {
        this.getCountries();
    }
});