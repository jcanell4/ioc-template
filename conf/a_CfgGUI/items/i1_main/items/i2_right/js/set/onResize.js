if(this.dispatcher.getGlobalState().login){
  var user=this.dispatcher.getGlobalState().username;
  this.dispatcher.almacenLocal.setUserRightPanelSize(user,_data.size.w);
}

