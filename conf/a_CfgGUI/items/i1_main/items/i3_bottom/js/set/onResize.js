if(this.dispatcher.getGlobalState().login){
  var user=this.dispatcher.getGlobalState().username;
  this.dispatcher.almacenLocal.setUserBottomPanelSize(user,_data.size.h);
}

