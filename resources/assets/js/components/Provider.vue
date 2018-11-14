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
                  <i class="fa fa-align-justify"></i> Proveedores
                  <button type="button" @click="openModal('person', 'store')" class="btn btn-secondary">
                      <i class="icon-plus"></i>&nbsp;Nuevo
                  </button>
              </div>
              <div class="card-body">
                  <div class="form-group row">
                      <div class="col-md-6">
                          <div class="input-group">
                              <select class="form-control col-md-3" v-model="criteria">
                                <option value="name">Nombre</option>
                                <option value="document_num">DNI</option>
                              </select>
                              <!-- @ es una abreviacion de V-ON -->
                              <input type="text" v-model="search" @keyup.enter="personList( 1 , search , criteria )"  class="form-control" placeholder="Texto a buscar">
                              <button type="submit" @click="personList( 1 , search , criteria )" class="btn btn-primary">
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
                              <th>CUIT</th>
                              <th>Direccion</th>
                              <th>Telefono</th>
                              <th>Email</th>
                              <th>Contacto</th>
                              <th>Estado</th>
                          </tr>
                      </thead>
                      <tbody>
                          <tr v-for="person in arrayPerson" :key="person.id">
                              <td>
                                  <button type="button" @click="openModal('person', 'update', person)" class="btn btn-warning btn-sm">
                                    <i class="icon-pencil"></i>
                                  </button> &nbsp;
                                  <template v-if="person.active">
                                    <button @click="personDesactivate(person.id)" type="button" class="btn btn-danger btn-sm">
                                      <i class="icon-trash"></i>
                                    </button>
                                  </template>
                                  <template v-else>
                                    <button @click="personActivate(person.id)" type="button" class="btn btn-info btn-sm">
                                      <i class="fa fa-check"></i>
                                    </button>
                                  </template>
                              </td>
                              <td v-text="person.name"></td>
                              <td v-text="person.document_num"></td>
                              <td v-text="person.direction"></td>
                              <td v-text="person.phone"></td>
                              <td v-text="person.email"></td>
                              <td v-text="person.contact"></td>
                              <td>
                                  <div v-if="person.active">
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
                                  <input type="text" v-model="name" class="form-control" placeholder="Nombre">
                              </div>
                          </div>

                          <div class="form-group row">
                              <label class="col-md-3 form-control-label" for="text-input">CUIT (*)</label>
                              <div class="col-md-9">
                                  <input type="text" v-model="document_num" class="form-control" placeholder="Numero de documento">
                              </div>
                          </div>
                          <div class="form-group row">
                              <label class="col-md-3 form-control-label" for="text-input">Direccion (*)</label>
                              <div class="col-md-9">
                                  <input type="text" v-model="direction" class="form-control" placeholder="Direccion">
                              </div>
                          </div>
                          <div class="form-group row">
                              <label class="col-md-3 form-control-label" for="text-input">Telefono (*)</label>
                              <div class="col-md-9">
                                  <input type="text" v-model="phone" class="form-control" placeholder="telefono">
                              </div>
                          </div>
                          <div class="form-group row">
                              <label class="col-md-3 form-control-label" for="text-input">Email (*)</label>
                              <div class="col-md-9">
                                  <input type="text" v-model="email" class="form-control" placeholder="Email">
                              </div>
                          </div>
                          <div class="form-group row">
                              <label class="col-md-3 form-control-label" for="text-input">Contacto (*)</label>
                              <div class="col-md-9">
                                  <input type="text" v-model="contact" class="form-control" placeholder="Nombre del contacto">
                              </div>
                          </div>
                          <div class="form-group row">
                              <label class="col-md-3 form-control-label" for="text-input">Telefon de contacto (*)</label>
                              <div class="col-md-9">
                                  <input type="text" v-model="contact_phone" class="form-control" placeholder="Telefono del contacto">
                              </div>
                          </div>
                          <div v-show="personError" class="form-group row div-error">
                            <div class="text-center text-error">
                              <div v-for="error in personErrorMsg" :key="error" v-text="error"></div>
                            </div>
                          </div>
                      </form>
                  </div>
                  <div class="modal-footer">
                      <button type="button" class="btn btn-secondary" @click="closeModal()">Cerrar</button>
                      <button v-if="actionType==1" type="button" class="btn btn-primary" @click="storePerson()">Guardar</button>
                      <button v-if="actionType==2" type="button" class="btn btn-primary" @click="updatePerson()">Actualizar</button>
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
                      <h4 class="modal-title">Eliminar persona</h4>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                      </button>
                  </div>
                  <div class="modal-body">
                      <p>Estas seguro de eliminar la persona?</p>
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
        person_id : 0,
        name : '',
        document_type : '',
        document_num : '',
        direction : '',
        phone : '',
        email : '',
        contact: '',
        contact_phone: '',
        arrayPerson: [],
        modal : 0,
        modalTitle : '',
        actionType : 0,
        personError : 0,
        personErrorMsg : [],
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
      personList(page = 1, search = '', criteria = 'name'){
        let me = this
        var url = 'index.php/provider?page=' + page + '&search=' + search + '&criteria=' + criteria
        axios.get(url).then( function (response){
          var resp = response.data
          me.arrayPerson = resp.persons.data
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
        me.personList(page, search , criteria)
      },
      storePerson(){
        if (this.validatePerson()) {
          return
        }
        let me = this
          axios.post('index.php/provider/store', {
            'name': this.name,
            'document_type': this.document_type,
            'document_num': this.document_num,
            'document_type': this.document_type,
            'direction': this.direction,
            'phone': this.phone,
            'email': this.email,
            'contact': this.contact,
            'contact_phone': this.contact_phone,
          }).then(function(response){
            me.closeModal()
            me.personList(1, '', 'name')
          }).catch(function(error){
            console.log(error)
          })
      },
      updatePerson(){
        if (this.validatePerson()) {
          return
        }
        let me = this
          axios.put('index.php/provider/update', {
            'id' : this.person_id,
            'name': this.name,
            'document_type': this.document_type,
            'document_num': this.document_num,
            'document_type': this.document_type,
            'direction': this.direction,
            'phone': this.phone,
            'email': this.email,
            'contact': this.contact,
            'contact_phone': this.contact_phone,
          }).then(function(response){
            me.closeModal()
            me.personList(1, '', 'name')
          }).catch(function(error){
            console.log(error)
          })
      },
      // Validamos que el name no sea vacio
      validatePerson(){
        this.personError = 0
        this.personErrorMsg = []
        // Si el nombre esta vacio asignamos el msg de error al array
        if (!this.name) this.personErrorMsg.push('El nombre de la persona no puede estar vacio.')

        // Si el array tiene un mensaje adentro personError le asignamos true/1
        if(this.personErrorMsg.length) this.personError = 1
        return this.personError
      },
      closeModal(){
        this.modal = 0
        this.modalTitle = ''
        this.name = ''
        this.document_type = ''
        this.document_num = ''
        this.direction = ''
        this.phone = ''
        this.email = ''
        this.contact = ''
        this.contact_phone = ''
        this.personError = 0
        this.personErrorMsg = []
      },
      openModal(model, action, data = []){
        switch(model) {
          case "person":
          {
            switch(action){
              case "store":
              {
                this.actionType = 1
                this.modal = 1
                this.modalTitle = 'Registrar persona'
                this.name = ''
                this.document_type = ''
                this.document_num = ''
                this.direction = ''
                this.phone = ''
                this.email = ''
                this.contact = ''
                this.contact_phone = ''
                break
              }
              case "update":
              {
                this.actionType = 2
                this.modal = 1
                this.modalTitle = 'Actualizar persona'
                this.person_id = data.id
                this.name = data.name
                this.document_type = data.document_type
                this.document_num = data.document_num
                this.direction = data.direction
                this.phone = data.phone
                this.email = data.email
                this.contact = ''
                this.contact_phone = ''
                break
              }
            }
          }
        }
      }, // end openModal
      personDesactivate(id){
        swal({
          title: "Estas seguro de desactivar esta persona?",
          icon: "warning",
          buttons: true,
          dangerMode: true,
        })
        .then((willDelete) => {
          if (willDelete) {
            let me = this
            axios.put('index.php/provider/deactivate', {
              'id' : id
            }).then(function(response){
              swal("persona desactivado!", {
              icon: "success",
              });
              me.personList()
            }).catch(function(error){
              console.log(error)
            })
          }
        });
      },
      personActivate(id){
        swal({
          title: "Estas seguro de activar esta persona?",
          icon: "warning",
          buttons: true,
          dangerMode: true,
        })
        .then((willDelete) => {
          if (willDelete) {
            let me = this
            axios.put('index.php/provider/activate', {
              'id' : id
            }).then(function(response){
              swal("persona activado!", {
              icon: "success",
              });
              me.personList()
            }).catch(function(error){
              console.log(error)
            })
          }
        });
      }
    },
    mounted() {
      this.personList( 1 , this.search  , this.criteria)
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