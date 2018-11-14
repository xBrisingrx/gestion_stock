<template>
  <main class="main">
      <!-- Breadcrumb -->
      <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="#">Escritorio</a></li>
      </ol>
      <div class="container-fluid">
          <!-- Ejemplo de tabla Listado -->
          <div class="card">
              <div class="card-header">
                  <i class="fa fa-align-justify"></i> Artículos
                  <button type="button" @click="openModal('article', 'store')" class="btn btn-secondary">
                      <i class="icon-plus"></i>&nbsp;Nuevo
                  </button>
              </div>
              <div class="card-body">
                  <div class="form-group row">
                      <div class="col-md-6">
                          <div class="input-group">
                              <select class="form-control col-md-3" v-model="criteria">
                                <option value="name">Nombre</option>
                                <option value="description">Descripción</option>
                              </select>
                              <!-- @ es una abreviacion de V-ON -->
                              <input type="text" v-model="search" @keyup.enter="articleList( 1 , search , criteria )"  class="form-control" placeholder="Texto a buscar">
                              <button type="submit" @click="articleList( 1 , search , criteria )" class="btn btn-primary">
                                <i class="fa fa-search"></i> Buscar
                              </button>
                          </div>
                      </div>
                  </div>
                  <table class="table table-bordered table-striped table-sm">
                      <thead>
                          <tr>
                              <th>Opciones</th>
                              <th>Código</th>
                              <th>Nombre</th>
                              <th>Categoría</th>
                              <th>Precio compra</th>
                              <th>Stock</th>
                              <th>Descripción</th>
                              <th>Estado</th>
                          </tr>
                      </thead>
                      <tbody>
                          <tr v-for="article in arrayArticle" :key="article.id">
                              <td>
                                  <button type="button" @click="openModal('article', 'update', article)" class="btn btn-warning btn-sm">
                                    <i class="icon-pencil"></i>
                                  </button> &nbsp;
                                  <template v-if="article.active">
                                    <button @click="articleDesactivate(article.id)" type="button" class="btn btn-danger btn-sm">
                                      <i class="icon-trash"></i>
                                    </button>
                                  </template>
                                  <template v-else>
                                    <button @click="articleActivate(article.id)" type="button" class="btn btn-info btn-sm">
                                      <i class="fa fa-check"></i>
                                    </button>
                                  </template>
                              </td>
                              <td v-text="article.code"></td>
                              <td v-text="article.name"></td>
                              <td v-text="article.category_name"></td>
                              <td v-text="article.sale_price"></td>
                              <td v-text="article.stock"></td>
                              <td v-text="article.description"></td>
                              <td>
                                  <div v-if="article.active">
                                    <span class="badge badge-success">Activo</span>
                                  </div>
                                  <div v-else>
                                    <span class="badge badge-danger">Inactivo</span>
                                  </div>
                              </td>
                          </tr>
                      </tbody>
                  </table>
                  <nav>
                      <ul class="pagination">
                          <li class="page-item" v-if="pagination.current_page > 1">
                              <a class="page-link" href="#" @click.prevent="changePage(pagination.current_page - 1 , search , criteria )">Ant</a>
                          </li>
                          <li class="page-item" v-for="page in pagesNumber" :key="page" :class="[page == isActived ? 'active' : '']">
                              <a class="page-link" href="#" @click.prevent="changePage(page , search , criteria )" v-text="page"></a>
                          </li>
                          <li class="page-item" v-if="pagination.current_page < pagination.last_page">
                              <a class="page-link" href="#" @click.prevent="changePage(pagination.current_page + 1 , search , criteria )">Sig</a>
                          </li>
                      </ul>
                  </nav>
              </div>
          </div>
          <!-- Fin ejemplo de tabla Listado -->
      </div>
      <!--Inicio del modal agregar/actualizar-->
      <div class="modal fade" tabindex="-1" :class="{'show' : modal}" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
          <div class="modal-dialog modal-primary modal-lg" role="document">
              <div class="modal-content">
                  <div class="modal-header">
                      <h4 class="modal-title" v-text="modalTitle"></h4>
                      <button type="button" class="close" aria-label="Close" @click="closeModal()">
                        <span aria-hidden="true">×</span>
                      </button>
                  </div>
                  <div class="modal-body">
                      <form action="" method="post" enctype="multipart/form-data" class="form-horizontal">
                          <div class="form-group row">
                              <label class="col-md-3 form-control-label" for="text-input">Categoria (*)</label>
                              <div class="col-md-9">
                                  <select class="form-control" v-model="category_id">
                                    <option value="0" disabled>Seleccione</option>
                                    <option v-for="category in array_categories" :key="category.id" :value="category.id" v-text="category.name"></option>
                                  </select>
                              </div>
                          </div>
                          <div class="form-group row">
                              <label class="col-md-3 form-control-label" for="text-input">Codigo (*)</label>
                              <div class="col-md-9">
                                  <input type="text" v-model="code" class="form-control" placeholder="Codigo del articulo">
                                  <barcode :value="code" :options="{ format: 'EAN-13' }">
                                    Generando código de barras...
                                  </barcode>
                              </div>
                          </div>
                          <div class="form-group row">
                              <label class="col-md-3 form-control-label" for="text-input">Nombre (*)</label>
                              <div class="col-md-9">
                                  <input type="text" v-model="name" class="form-control" placeholder="Nombre del articulo">
                              </div>
                          </div>
                          <div class="form-group row">
                              <label class="col-md-3 form-control-label" for="text-input">Precio de compra (*)</label>
                              <div class="col-md-9">
                                  <input type="number" v-model="sale_price" class="form-control">
                              </div>
                          </div>
                          <div class="form-group row">
                              <label class="col-md-3 form-control-label" for="text-input">Stock (*)</label>
                              <div class="col-md-9">
                                  <input type="number" v-model="stock" class="form-control">
                              </div>
                          </div>
                          <div class="form-group row">
                              <label class="col-md-3 form-control-label" for="email-input">Descripción</label>
                              <div class="col-md-9">
                                  <input type="text" v-model="description" class="form-control" placeholder="Ingrese descripcion">
                              </div>
                          </div>
                          <div v-show="articleError" class="form-group row div-error">
                            <div class="text-center text-error">
                              <div v-for="error in articleErrorMsg" :key="error" v-text="error"></div>
                            </div>
                          </div>
                      </form>
                  </div>
                  <div class="modal-footer">
                      <button type="button" class="btn btn-secondary" @click="closeModal()">Cerrar</button>
                      <button v-if="actionType==1" type="button" class="btn btn-primary" @click="storeArticle()">Guardar</button>
                      <button v-if="actionType==2" type="button" class="btn btn-primary" @click="updateArticle()">Actualizar</button>
                  </div>
              </div>
              <!-- /.modal-content -->
          </div>
          <!-- /.modal-dialog -->
      </div>
      <!--Fin del modal-->
      <!-- Inicio del modal Eliminar -->
      <div class="modal fade" id="modalEliminar" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
          <div class="modal-dialog modal-danger" role="document">
              <div class="modal-content">
                  <div class="modal-header">
                      <h4 class="modal-title">Eliminar artículo</h4>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                      </button>
                  </div>
                  <div class="modal-body">
                      <p>Estas seguro de eliminar la artículo?</p>
                  </div>
                  <div class="modal-footer">
                      <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                      <button type="button" class="btn btn-danger">Eliminar</button>
                  </div>
              </div>
              <!-- /.modal-content -->
          </div>
          <!-- /.modal-dialog -->
      </div>
      <!-- Fin del modal Eliminar -->

  </main>
</template>

<script>
  import VueBarcode from 'vue-barcode'
  export default {
    data(){
      return {
        article_id : 0,
        category_id : 0,
        category_name : '',
        code : '',
        sale_price : '',
        stock : '',
        name : '',
        description : '',
        arrayArticle: [],
        modal : 0,
        modalTitle : '',
        actionType : 0,
        articleError : 0,
        articleErrorMsg : [],
        pagination : {
          'total' : 0,
          'current_page' : 0,
          'per_page' : 0,
          'last_page' : 0,
          'from' : 0,
          'to' : 0
        },
        offset : 3,
        criteria: 'name',
        search: '',
        array_categories : []
      }
    },
    components: {
      'barcode': VueBarcode
    },
    computed : {
      isActived : function(){
        return this.pagination.current_page
      },
      // Calcula los elementos de la paginacion
      pagesNumber : function() {
        if (!this.pagination.to) {
          return []
        }

        var from = this.pagination.current_page - this.offset
        if (from < 1) {
          from = 1
        }
        var to = from + (this.offset * 2)
        if (to >= this.pagination.last_page) {
          to = this.pagination.last_page
        }

        var pagesArray = []
        while(from <= to) {
          pagesArray.push(from)
          from++
        }
        return pagesArray
      }
    },
    methods : {
      articleList(page = 1, search = '', criteria = 'name'){
        let me = this
        var url = 'index.php/article?page=' + page + '&search=' + search + '&criteria=' + criteria
        axios.get(url).then( function (response){
          var resp = response.data
          me.arrayArticle = resp.articles.data
          me.pagination = resp.pagination
        })
        .catch(function(error){
          console.log(error)
        })
      },
      categorySelect(){
        let me = this
        var url = 'index.php/category/getCategories'
        axios.get(url).then( function (response){
          var resp = response.data
          me.array_categories = resp.categories
        })
        .catch(function(error){
          console.log(error)
        })
      },
      changePage(page, search, criteria){
        let me = this
        //Actualiza la pagina actual
        me.pagination.current_page = page
        // Enviar la peticion para visualizar la data de esa pagina
        me.articleList(page, search , criteria)
      },
      storeArticle(){
        if (this.validateArticle()) {
          return
        }
        let me = this
          axios.post('index.php/article/store', {
            'category_id' : this.category_id,
            'code' : this.code,
            'name': this.name,
            'stock' : this.stock,
            'sale_price' : this.sale_price,
            'description': this.description
          }).then(function(response){
            me.closeModal()
            me.articleList(1, '', 'name')
          }).catch(function(error){
            console.log(error)
          })
      },
      updateArticle(){
        if (this.validateArticle()) {
          return
        }
        let me = this
          axios.put('index.php/article/update', {
            'id' : this.article_id,
            'category_id' : this.category_id,
            'code' : this.code,
            'name': this.name,
            'stock' : this.stock,
            'sale_price' : this.sale_price,
            'description': this.description
          }).then(function(response){
            me.closeModal()
            me.articleList()
          }).catch(function(error){
            console.log(error)
          })
      },
      // Validamos que el name no sea vacio
      validateArticle(){
        this.articleError = 0
        this.articleErrorMsg = []
        // Si el nombre esta vacio asignamos el msg de error al array
        if (this.category_id == 0) this.articleErrorMsg.push('Seleccione una categoria.')
        if (!this.name) this.articleErrorMsg.push('El nombre del articulo no puede estar vacio.')
        if (!this.stock) this.articleErrorMsg.push('El stock debe ser un numero y no puede estar vacio.')
        if (!this.sale_price) this.articleErrorMsg.push('El precio de venta debe ser un numero y no puede estar vacio.')
        // Si el array tiene un mensaje adentro articleError le asignamos true/1
        if(this.articleErrorMsg.length) this.articleError = 1
        return this.articleError
      },
      closeModal(){
        this.modal = 0
        this.modalTitle = ''
        this.category_id = 0
        this.category_name = ''
        this.code = 0
        this.name = ''
        this.sale_price = 0
        this.stock = 0
        this.description =''
        this.name = ''
        this.description = ''
        this.articleError = 0
        this.articleErrorMsg = []
      },
      openModal(model, action, data = []){
        switch(model) {
          case "article":
          {
            switch(action){
              case "store":
              {
                this.actionType = 1
                this.modal = 1
                this.modalTitle = 'Registrar articulo'
                this.category_id = 0
                this.category_name = ''
                this.code = 0
                this.name = ''
                this.sale_price = 0
                this.stock = 0
                this.description =''
                break
              }
              case "update":
              {
                this.actionType = 2
                this.modal = 1
                this.modalTitle = 'Actualizar articulo'
                this.article_id = data.id
                this.category_id = data.category_id
                this.category_name = data.category_name
                this.code = data.code
                this.name = data.name
                this.sale_price = data.sale_price
                this.stock = data.stock
                this.description = data.description
                break
              }
            }
          }
        }
        this.categorySelect()
      }, // end openModal
      articleDesactivate(id){
        swal({
          title: "Estas seguro de desactivar esta categoria?",
          icon: "warning",
          buttons: true,
          dangerMode: true,
        })
        .then((willDelete) => {
          if (willDelete) {
            let me = this
            axios.put('index.php/article/deactivate', {
              'id' : id
            }).then(function(response){
              swal("Categoria desactivada!", {
              icon: "success",
              });
              me.articleList()
            }).catch(function(error){
              console.log(error)
            })
          }
        });
      },
      articleActivate(id){
        swal({
          title: "Estas seguro de activar esta categoria?",
          icon: "warning",
          buttons: true,
          dangerMode: true,
        })
        .then((willDelete) => {
          if (willDelete) {
            let me = this
            axios.put('index.php/article/activate', {
              'id' : id
            }).then(function(response){
              swal("Categoria activada!", {
              icon: "success",
              });
              me.articleList()
            }).catch(function(error){
              console.log(error)
            })
          }
        });
      }
    },
    mounted() {
      this.articleList( 1 , this.search  , this.criteria)
    }
  }
</script>

<style>
  .modal-content{
    width: 100% !important;
    position: absolute !important;
  }
  .show{
    display: list-item !important;
    opacity: 1 !important;
    position: absolute !important;
    background-color: #3c29297a !important;
  }
  .div-error{
    display: flex;
    justify-content: center;
  }
  .text-error{
    color: red !important;
    font-weight: bold;
  }
</style>