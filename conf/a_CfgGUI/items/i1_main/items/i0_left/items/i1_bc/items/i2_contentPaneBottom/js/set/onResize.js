if(this.dispatcher.getGlobalState().login){
  var user=this.dispatcher.getGlobalState().userId;
  this.dispatcher.almacenLocal.setUserLeftBottomPanelSize(user,_data.size.h);
}

