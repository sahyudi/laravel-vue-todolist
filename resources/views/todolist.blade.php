<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Todo List</title>


    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>

    <!-- vue js -->
    <script src="https://cdn.jsdelivr.net/npm/vue/dist/vue.js"></script>

    <!-- Axios -->
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
</head>

<style>
    .todolist-wrapper {
        border: 1px solid #cccccc;
        min-height: 100px;
    }
</style>

<body>

    <div class="container">

        <div id="app">
            <div class="modal" id="modal-form">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Todo List Form</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="">Content</label>
                                <textarea v-model="content" class="form-control" rows="6"></textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <a href="javascript:;" @click="saveTodolist" class="btn btn-primary">Save TodoList</a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-3"></div>
                <div class="col-sm-6">

                    <div class="text-right mb-3 mt-3">
                        <a href="javascript:;" @click="openForm" class="btn btn-primary">Tambah TodoList</a>
                    </div>

                    <div class="text-center mb-3">
                        <input type="text" v-model="search" @keyup="findData" placeholder="cari disini" class="form-control">

                    </div>
                    <div class="todolist-wrapper">
                        <table class="table table-striped table-bordered">
                            <tbody>
                                <tr v-for="item in data_list">
                                    <td>@{{item.content}}
                                        <a href="javascript:;" @click="editData(item.id)" class="btn btn-primary">Edit</a>
                                        <a href="javascript:;" @click="deleteData(item.id)" class="btn btn-danger">Delete</a>
                                    </td>
                                </tr>
                                <tr v-if="!data_list.length">
                                    <td>Data masih kosong</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="col-sm-3"></div>
            </div>
        </div>
    </div>

    <script>
        var vue = new Vue({
            el: '#app',
            mounted() {
                this.getDatalist();
            },
            data: {
                data_list: [],
                content: "",
                id: "",
                search: ""
            },
            methods: {
                openForm: function() {
                    $('#modal-form').modal('show');
                },
                findData: function() {
                    this.getDatalist();
                },
                deleteData: function(id) {
                    if (confirm('Apakah data akan dihapus ??')) {
                        axios.post("{{url('api/todolist/delete')}}/" + id)
                            .then(resp => {
                                alert(resp.data.message);
                                this.getDatalist();
                            })
                            .catch(err => {
                                alert('Terjadi kesalahan saat mengapus data ' + err);
                            })
                            .finally(() => {
                                this.getDatalist();
                            })
                    }
                },
                editData: function(id) {
                    this.id = id;
                    axios.get("{{url('api/todolist/read')}}/" + this.id)
                        .then(resp => {
                            var item = resp.data;
                            this.content = item.content;
                            $('#modal-form').modal('show');
                        })
                        .catch(err => {
                            alert('Terjadi kesalahan saat get data');
                        })
                },
                saveTodolist: function() {
                    var form_data = new FormData;
                    form_data.append("content", this.content);

                    if (this.id) {
                        axios.post("{{url('api/todolist/update')}}/" + this.id, form_data)
                            .then(resp => {
                                this.content = '';
                                this.getDatalist();
                            })
                            .catch(err => {
                                alert('Terjadi kesalahan saat update data ' + err);
                            })
                            .finally(() => {
                                $('#modal-form').modal('hide');
                            })
                    } else {
                        axios.post("{{url('api/todolist/create')}}", form_data)
                            .then(resp => {
                                this.content = '';
                                this.getDatalist();
                            })
                            .catch(err => {
                                alert('Terjadi kesalahan saat insert ' + err);
                            })
                            .finally(() => {
                                $('#modal-form').modal('hide');
                            })
                    }

                },
                getDatalist: function() {
                    axios.get("{{url('api/todolist/list')}}?search=" + this.search)
                        .then(resp => {
                            this.data_list = resp.data
                        })
                        .catch(err => {
                            alert('Kesalahan saat menampilkan')
                        })
                }
            }
        })
    </script>
</body>

</html>