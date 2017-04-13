if(this.dispatcher.getGlobalState().login){
  var user=this.dispatcher.getGlobalState().username;
  this.dispatcher.almacenLocal.setUserLeftPanelSize(user,_data.size.w);
}

