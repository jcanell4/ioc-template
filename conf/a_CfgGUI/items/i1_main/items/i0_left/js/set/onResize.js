if(this.dispatcher.getGlobalState().login){
  var user=this.dispatcher.getGlobalState().userId;
  this.dispatcher.almacenLocal.setUserLeftPanelSize(user,_data.size.w);
}

