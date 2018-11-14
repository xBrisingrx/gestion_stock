@extends('main')
@section('content')
  <template v-if="menu==0">
     <h1>  </h1>
  </template>

  <template v-if="menu==1">
    <category> </category>
  </template>

  <template v-if="menu==2">
    <myarticle> </myarticle>
  </template>

  <template v-if="menu==3">
    <h1> Menu 3 </h1>
  </template>

  <template v-if="menu==4">
    <provider> </provider>
  </template>

  <template v-if="menu==5">
    <h1> Menu 5 </h1>
  </template>

  <template v-if="menu==6">
    <client> </client>
  </template>

  <template v-if="menu==7">
    <user> </user>
  </template>

  <template v-if="menu==8">
    <rol> </rol>
  </template>

  <template v-if="menu==9">
    <h1> Menu 9 </h1>
  </template>

  <template v-if="menu==10">
    <h1> Menu 10 </h1>
  </template>

  <template v-if="menu==11">
    <h1> Menu 11 </h1>
  </template>

  <template v-if="menu==12">
    <h1> Menu 12 </h1>
  </template>
@endsection