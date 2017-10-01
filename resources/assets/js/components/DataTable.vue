<template>

    <div class="panel panel-default">
        <div class="panel-heading"><b>{{ response.table }}</b></div>

        <div class="panel-body">
            <div class="row">
                <div class="form-group col-md-10">
                    <label for="filter">Quick search current results</label>
                    <input type="text" id="filter" class="form-control" v-model="quickSearchQuery">
                </div> 
                <div class="form-group col-md-2">
                    <label for="limit">Limit</label>
                    <select id="limit" class="form-control" v-model="limit" @change="getRecords()">
                        <option value="50">50</option>
                        <option value="100">100</option>
                        <option value="1000">1000</option>
                        <option value="">All</option>
                    </select>
                </div>
            </div>

            <div class="table-responsive"">
             <label for="table"></label>
                <table class="table table-striped">
                 <thead>
                   <tr> 
                     <th v-for="column in response.displayable"><span class="sortable" @click="setSortKey(column)">{{ column }}</span>
                        <div 
                            class="arrow" 
                            v-if="column === sort.key"
                            :class="{ 'arrow--asc': sort.type === 'asc', 'arrow--desc': sort.type === 'desc' }"
                        ></div>
                     </th>
                     <th> &nbsp; </th>
                   </tr>
                 </thead>
                 <tbody>
                   <tr v-for="record in sortedRecords">
                     <td v-for="columnValue, column in record">{{columnValue}}</td>
                    
                     <button class="btn btn-default " id="edit">Edit</button>
                     <button class="btn btn-default " id="delete">Delete</button>    
                   </tr>
                 </tbody>
                </table>
            </div> 
        </div>
    </div>
</template>

<script>
    import queryString from 'query-string'
    export default {
        props:['endpoint'],

        data () {
            return {
                response: {
                    table: '',
                    displayable: [],
                    records: [],
                },
                sort: {
                        key: 'id',
                        type: 'asc',
                },
                quickSearchQuery: '',
                limit: 100
            }
        },

        computed: {
            sortedRecords () {
                
                let data = this.response.records;

                data = data.filter((row) => {
                    return Object.keys(row).some((key) => {
                        return String(row[key]).toLowerCase().indexOf(this.quickSearchQuery.toLowerCase()) > -1
                    })
                    
                })

                if (this.sort.key) {
                    data = _.orderBy(data, (i) => {
                        
                        let value = i[this.sort.key];

                        if (!isNaN(parseFloat(value))) {
                            return parseFloat(value);
                        }

                        return String(i[this.sort.key]).toLowerCase()
                    }, this.sort.type)
                }

                return data
            }
        },

        mounted() {
          this.getRecords()
        },

        methods: {
            getRecords () {
                return axios.get(`${this.endpoint}?${this.getQueryParameters()}`).then((response) => { 
                    this.response = response.data.data
                    this.displayable = response.data.data.displayable
                })
            },
            getQueryParameters () {
                return queryString.stringify({
                    limit: this.limit
                })
            },
            setSortKey (column) {
                this.sort.type = ( this.sort.type === 'asc' & this.sort.key == column) ? 'desc' : 'asc';
                  this.sort.key = column;
            }
            
         }
    }
</script>

<style lang="scss">
    .sortable {
        cursor: pointer;
    }

    .arrow {
        display: inline-block;
        vertical-align: middle;
        width: 0px;
        height: 0px;
        margin-left: 5px;
        opacity: 0.6; 

        &--asc {
            border-left: 4px solid transparent;
            border-right: 4px solid transparent;
            border-bottom: 4px solid #222;
        }

        &--desc {
            border-left: 4px solid transparent;
            border-right: 4px solid transparent;
            border-top: 4px solid #222;
        }
    }
</style>
