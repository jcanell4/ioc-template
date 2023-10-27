if(this.dispatcher.getGlobalState().login){
  var user=this.dispatcher.getGlobalState().userId;
  this.dispatcher.almacenLocal.setUserBottomPanelSize(user,_data.size.h);
}

