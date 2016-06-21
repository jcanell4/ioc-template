if(this.dispatcher.getGlobalState().login){
  var user=this.dispatcher.getGlobalState().userId;
  this.dispatcher.almacenLocal.setUserRightPanelSize(user,_data.size.w);
}

