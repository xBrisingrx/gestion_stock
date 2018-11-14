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
                  <i class="fa fa-align-justify"></i> Categorías
                  <button type="button" @click="openModal('category', 'store')" class="btn btn-secondary">
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
                              <input type="text" v-model="search" @keyup.enter="categoryList( 1 , search , criteria )"  class="form-control" placeholder="Texto a buscar">
                              <button type="submit" @click="categoryList( 1 , search , criteria )" class="btn btn-primary">
                                <i class="fa fa-search"></i> Buscar
                              </button>
                          </div>
                      </div>
                  </div>
                  <table class="table table-bordered table-striped table-sm">
                      <thead>
                          <tr>
                              <th>Opciones</th>
                              <th>Nombre</th>
                              <th>Descripción</th>
                              <th>Estado</th>
                          </tr>
                      </thead>
                      <tbody>
                          <tr v-for="category in arrayCategory" :key="category.id">
                              <td>
                                  <button type="button" @click="openModal('category', 'update', category)" class="btn btn-warning btn-sm">
                                    <i class="icon-pencil"></i>
                                  </button> &nbsp;
                                  <template v-if="category.active">
                                    <button @click="categoryDesactivate(category.id)" type="button" class="btn btn-danger btn-sm">
                                      <i class="icon-trash"></i>
                                    </button>
                                  </template>
                                  <template v-else>
                                    <button @click="categoryActivate(category.id)" type="button" class="btn btn-info btn-sm">
                                      <i class="fa fa-check"></i>
                                    </button>
                                  </template>
                              </td>
                              <td v-text="category.name"></td>
                              <td v-text="category.description"></td>
                              <td>
                                  <div v-if="category.active">
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
                              <label class="col-md-3 form-control-label" for="text-input">Nombre (*)</label>
                              <div class="col-md-9">
                                  <input type="text" v-model="name" class="form-control" placeholder="Nombre de categoría">
                              </div>
                          </div>
                          <div class="form-group row">
                              <label class="col-md-3 form-control-label" for="email-input">Descripción</label>
                              <div class="col-md-9">
                                  <input type="text" v-model="description" class="form-control" placeholder="Ingrese descripcion">
                              </div>
                          </div>
                          <div v-show="categoryError" class="form-group row div-error">
                            <div class="text-center text-error">
                              <div v-for="error in categoryErrorMsg" :key="error" v-text="error"></div>
                            </div>
                          </div>
                      </form>
                  </div>
                  <div class="modal-footer">
                      <button type="button" class="btn btn-secondary" @click="closeModal()">Cerrar</button>
                      <button v-if="actionType==1" type="button" class="btn btn-primary" @click="storeCategory()">Guardar</button>
                      <button v-if="actionType==2" type="button" class="btn btn-primary" @click="updateCategory()">Actualizar</button>
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
                      <h4 class="modal-title">Eliminar Categoría</h4>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                      </button>
                  </div>
                  <div class="modal-body">
                      <p>Estas seguro de eliminar la categoría?</p>
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
  export default {
    data(){
      return {
        category_id : 0,
        name : '',
        description : '',
        arrayCategory: [],
        modal : 0,
        modalTitle : '',
        actionType : 0,
        categoryError : 0,
        categoryErrorMsg : [],
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
        search: ''
      }
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
      categoryList(page = 1, search = '', criteria = 'name'){
        let me = this
        var url = 'index.php/category?page=' + page + '&search=' + search + '&criteria=' + criteria
        axios.get(url).then( function (response){
          var resp = response.data
          me.arrayCategory = resp.categories.data
          me.pagination = resp.pagination
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
        me.categoryList(page, search , criteria)
      },
      storeCategory(){
        if (this.validateCategory()) {
          return
        } 
        let me = this
          axios.post('index.php/category/store', {
            'name': this.name,
            'description': this.description
          }).then(function(response){
            me.closeModal()
            me.categoryList()
          }).catch(function(error){
            console.log(error)
          })
      },
      updateCategory(){
        if (this.validateCategory()) {
          return
        }
        let me = this
          axios.put('index.php/category/update', {
            'name': this.name,
            'description': this.description,
            'id' : this.category_id
          }).then(function(response){
            me.closeModal()
            me.categoryList()
          }).catch(function(error){
            console.log(error)
          })
      },
      // Validamos que el name no sea vacio
      validateCategory(){
        this.categoryError = 0
        this.categoryErrorMsg = []
        // Si el nombre esta vacio asignamos el msg de error al array
        if (!this.name) this.categoryErrorMsg.push('El nombre de la categoria no puede estar vacio.') 
        // Si el array tiene un mensaje adentro categoryError le asignamos true/1
        if(this.categoryErrorMsg.length) this.categoryError = 1
        return this.categoryError
      },
      closeModal(){
        this.modal = 0
        this.modalTitle = ''
        this.name = ''
        this.description = ''
        this.categoryError = 0
        this.categoryErrorMsg = []
      },
      openModal(model, action, data = []){
        switch(model) {
          case "category":
          {
            switch(action){
              case "store":
              {
                this.actionType = 1
                this.modal = 1
                this.modalTitle = 'Registrar categoria'
                this.name = ''
                this.description = ''
                break
              }
              case "update":
              {
                this.actionType = 2
                this.modal = 1
                this.modalTitle = 'Actualizar categoria'
                this.category_id = data.id
                this.name = data.name
                this.description = data.description
                break
              }
            }
          }
        }
      }, // end openModal
      categoryDesactivate(id){
        swal({
          title: "Estas seguro de desactivar esta categoria?",
          icon: "warning",
          buttons: true,
          dangerMode: true,
        })
        .then((willDelete) => {
          if (willDelete) {
            let me = this
            axios.put('index.php/category/deactivate', {
              'id' : id
            }).then(function(response){
              swal("Categoria desactivada!", {
              icon: "success",
              });
              me.categoryList()
            }).catch(function(error){
              console.log(error)
            })
          }
        });
      },
      categoryActivate(id){
        swal({
          title: "Estas seguro de activar esta categoria?",
          icon: "warning",
          buttons: true,
          dangerMode: true,
        })
        .then((willDelete) => {
          if (willDelete) {
            let me = this
            axios.put('index.php/category/activate', {
              'id' : id
            }).then(function(response){
              swal("Categoria activada!", {
              icon: "success",
              });
              me.categoryList()
            }).catch(function(error){
              console.log(error)
            })
          }
        });
      }
    },
    mounted() {
      this.categoryList( 1 , this.search  , this.criteria)
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