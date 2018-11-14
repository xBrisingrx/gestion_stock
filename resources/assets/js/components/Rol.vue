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
                  <i class="fa fa-align-justify"></i> Roles
                  <button type="button" @click="openModal('rol', 'store')" class="btn btn-secondary">
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
                              <input type="text" v-model="search" @keyup.enter="rolList( 1 , search , criteria )"  class="form-control" placeholder="Texto a buscar">
                              <button type="submit" @click="rolList( 1 , search , criteria )" class="btn btn-primary">
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
                          <tr v-for="rol in arrayRol" :key="rol.id">
                              <td>
                                  <button type="button" @click="openModal('rol', 'update', rol)" class="btn btn-warning btn-sm">
                                    <i class="icon-pencil"></i>
                                  </button> &nbsp;
                                  <template v-if="rol.active">
                                    <button @click="rolDesactivate(rol.id)" type="button" class="btn btn-danger btn-sm">
                                      <i class="icon-trash"></i>
                                    </button>
                                  </template>
                                  <template v-else>
                                    <button @click="rolActivate(rol.id)" type="button" class="btn btn-info btn-sm">
                                      <i class="fa fa-check"></i>
                                    </button>
                                  </template>
                              </td>
                              <td v-text="rol.name"></td>
                              <td v-text="rol.description"></td>
                              <td>
                                  <div v-if="rol.active">
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
                          <div v-show="rolError" class="form-group row div-error">
                            <div class="text-center text-error">
                              <div v-for="error in rolErrorMsg" :key="error" v-text="error"></div>
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
        rol_id : 0,
        name : '',
        description : '',
        arrayRol: [],
        modal : 0,
        modalTitle : '',
        actionType : 0,
        rolError : 0,
        rolErrorMsg : [],
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
      rolList(page = 1, search = '', criteria = 'name'){
        let me = this
        var url = 'index.php/rol?page=' + page + '&search=' + search + '&criteria=' + criteria
        axios.get(url).then( function (response){
          var resp = response.data
          me.arrayRol = resp.roles.data
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
        me.rolList(page, search , criteria)
      },
      storeRol(){
        if (this.validateRol()) {
          return
        }
        let me = this
          axios.post('index.php/rol/store', {
            'name': this.name,
            'description': this.description
          }).then(function(response){
            me.closeModal()
            me.rolList()
          }).catch(function(error){
            console.log(error)
          })
      },
      updateRol(){
        if (this.validateRol()) {
          return
        }
        let me = this
          axios.put('index.php/category/update', {
            'name': this.name,
            'description': this.description,
            'id' : this.category_id
          }).then(function(response){
            me.closeModal()
            me.rolList()
          }).catch(function(error){
            console.log(error)
          })
      },
      // Validamos que el name no sea vacio
      validateRol(){
        this.rolError = 0
        this.rolErrorMsg = []
        // Si el nombre esta vacio asignamos el msg de error al array
        if (!this.name) this.rolErrorMsg.push('El nombre de la rol no puede estar vacio.')
        // Si el array tiene un mensaje adentro rolError le asignamos true/1
        if(this.rolErrorMsg.length) this.rolError = 1
        return this.rolError
      },
      closeModal(){
        this.modal = 0
        this.modalTitle = ''
        this.name = ''
        this.description = ''
        this.rolError = 0
        this.rolErrorMsg = []
      },
      openModal(model, action, data = []){
        switch(model) {
          case "rol":
          {
            switch(action){
              case "store":
              {
                this.actionType = 1
                this.modal = 1
                this.modalTitle = 'Registrar rol'
                this.name = ''
                this.description = ''
                break
              }
              case "update":
              {
                this.actionType = 2
                this.modal = 1
                this.modalTitle = 'Actualizar rol'
                this.category_id = data.id
                this.name = data.name
                this.description = data.description
                break
              }
            }
          }
        }
      }, // end openModal
      rolDesactivate(id){
        swal({
          title: "Estas seguro de desactivar esta rol?",
          icon: "warning",
          buttons: true,
          dangerMode: true,
        })
        .then((willDelete) => {
          if (willDelete) {
            let me = this
            axios.put('index.php/rol/deactivate', {
              'id' : id
            }).then(function(response){
              swal("Rol desactivada!", {
              icon: "success",
              });
              me.rolList()
            }).catch(function(error){
              console.log(error)
            })
          }
        });
      },
      rolActivate(id){
        swal({
          title: "Estas seguro de activar esta rol?",
          icon: "warning",
          buttons: true,
          dangerMode: true,
        })
        .then((willDelete) => {
          if (willDelete) {
            let me = this
            axios.put('index.php/rol/activate', {
              'id' : id
            }).then(function(response){
              swal("Rol activada!", {
              icon: "success",
              });
              me.rolList()
            }).catch(function(error){
              console.log(error)
            })
          }
        });
      }
    },
    mounted() {
      this.rolList( 1 , this.search  , this.criteria)
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