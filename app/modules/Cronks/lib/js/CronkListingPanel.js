

Cronk.util.CronkListingPanel = function(c) {
	
	var CLP = this;
	
	Cronk.util.CronkListingPanel.superclass.constructor.call(this, c);
	
	this.stores = {};
	
	this.cronks = {};
	
	this.categories = {};
	
	this.default_act = -1;
	
	this.template = new Ext.XTemplate(
	    '<tpl for=".">',
	    	'<div class="{statusclass}" id="{name}">',
	    	'<div class="cronk-status-icon">',
        	'<div class="thumb"><img ext:qtip="{description}" src="{image}"></div>',
        	'<span class="x-editable">{name}</span>',
        	'</div>',
        	'</div>',
	    '</tpl>',
	    '<div class="x-clear"></div>'
	);
	
	this.loadData = function(url) {
		
		var mask = null;
		
		if (this.getEl()) {
			mask = new Ext.LoadMask(this.getEl(), {msg: _('Loading Cronks ...')});
			mask.show();
		}
		
		Ext.Ajax.request({
			url: url,
			callback: function(o,s,r) {
				if (mask)
					mask.hide();
					delete(mask);
			},
			success: function(r, o) {
				var data = Ext.decode(r.responseText);
				if (Ext.isDefined(data.categories) && Ext.isDefined(data.cronks)) {
					
					CLP.categories = data.categories;
					
					CLP.cronks = data.cronks;
					
					var i = 0;
					
					Ext.each(data.categories, function(item, index, arry) {
						if (Ext.isDefined(data.cronks[item.catid])) {
							if (this.getStore(item.catid)) {
								fillStore(item.catid, data.cronks[item.catid]);
							}
							else {
								fillStore(item.catid, data.cronks[item.catid]);
								createView(item.catid, item.title);
								
								if (Ext.isDefined(item.active) && item.active == true) this.default_act=i;
								
							}
							
							i++;
							
						}
					}, this);
					
					AppKit.util.Layout.doLayout(null, 200);
				}
			},
			failure: function (r, o) {
				var str = String.format(
					_('Could not load the cronk listing, following error occured: {0} ({1})'),
					r.status,
					r.statusText
				);
				
				parentcmp.add({
					layout: 'fit',
					html: str
				});
				parentcmp.doLayout();
				
				AppKit.notifyMessage('Ajax Error', str, { waitTime: 20 });
			},
			scope: CLP
		});
		
	};
	
	var fillStore = function(storeid, data) {
		
		if (Ext.isEmpty(CLP.stores[storeid])) {
			CLP.stores[storeid] = new Ext.data.JsonStore({
				autoDestroy: true,
				autoLoad: false,
			    root: 'rows',
			    idProperty: 'cronkid',
			    fields: [
			        'name', 'cronkid', 'description', 
			        'module', 'action', 'system', 'owner',
			        'categories', 'groupsonly', 'state',
			        {
						name: 'image_id',
						convert: function(v,record) {
							return record.image;
						}
					}, {
						name:'ae:parameter',
						convert:function(v,record) {
							if(!Ext.isObject(v))
								return v;
							for(var i in v) {
								if(Ext.isObject(v[i]))
									v[i] = Ext.encode(v[i]);
							}
							return v;
						}
					}, {
						name: 'image',
						convert: function(v, record){
							return AppKit.util.Dom.imageUrl(v);
						}
					}, {
						name: 'statusclass',
						convert: function(v, record) {
							var cls = 'cronk-preview';
							
							if (record.owner == true) {
								cls += ' cronk-item-owner';
							}
							
							if (record.system == true) {
								cls += ' cronk-item-system';
							}
							
							if (!record.system && !record.owner) {
								cls += ' cronk-item-shared';
							}
							
							return cls;
						}
					}
			    ]
			});
		}
		
		var store = CLP.stores[storeid];
		
		store.loadData(data);
	}
	
	var createView = function(storeid, title) {
		
		var store = CLP.getStore(storeid);
		
		CLP.add({
			title: String.format('{0} ({1})', title, store.getCount()),
			autoScroll:true,
			
			/*
			 * Bubbeling does not work because it collapse the 
			 * parent panel all the time
			 */
			listeners: {
				collapse: function(panel) {
					CLP.saveState();
				}
			},
			
			items: new Ext.DataView({
		        store: store,
		        tpl: CLP.template,
		        overClass:'x-view-over',
		        itemSelector:'div.cronk-preview',
		        emptyText: 'No data',
		       	cls: 'cronk-data-view',
		        border: false,
		        
		        // Create the drag zone
		        listeners: {
		        	render: CLP.initCronkDragZone.createDelegate(CLP),
		        	dblclick: CLP.dblClickHandler.createDelegate(CLP),
		        	contextmenu: CLP.handleContextmenu.createDelegate(CLP)
		        } 
		    }),
			border: false
		});
		
	}
	
	this.loadData(this.combinedProviderUrl);
	
	var act = false;
	
	CLP.on('afterrender', function() {
		if (!CLP.applyActiveItem() && this.default_act >= 0) {
			CLP.setActiveItem(this.default_act);
		}
	});
	
	var cb = Cronk.util.CronkBuilder.getInstance();
	
	cb.addListener('writeSuccess', function() {
		CLP.reloadAll();
	});
}

Ext.extend(Cronk.util.CronkListingPanel, Ext.Panel, {
	layout: 'accordion',
	layoutConfig: {
		animate: true,
		renderHidden: false,
		hideCollapseTool: true,
		fill: true
	},
	
	autoScroll: true,
	border: false,
	
	defaults: { border: false },
	
	stateful: true,
	
	stateEvents: ['collapse'],
	stateful: true,
	bubbleEvents: [],
	
	tbar: [{
		iconCls: 'icinga-icon-arrow-refresh',
		text: _('Reload'),
		handler: function(b, e) {
			var p = Ext.getCmp('cronk-listing-panel');
			p.reloadAll();
		}
	}],
	
	applyState: function(state) {
		if (!Ext.isEmpty(state.active_tab) && state.active_tab >= 0) {
			this.active_tab = state.active_tab;
		}
	},
	
	getState: function() {
		var active = this.getLayout().activeItem, i;
		this.items.each(function(item, index, l) {
			if (item == active) {
				i = index;
			}
		});
		
		if (typeof(i) !== "undefined" && i>=0) {
			return { active_tab: i }
		}
	},
	
	getStore : function(storeid) {
		if (Ext.isDefined(this.stores[storeid])) {
			return this.stores[storeid];
		}
	},
	
	dblClickHandler: function(oView, index, node, e) {
		var record = oView.getStore().getAt(index);
		
		var tabPanel = Ext.getCmp('cronk-tabs');
		
		if (tabPanel) {
			var panel = tabPanel.add({
				xtype: 'cronk',
				title: record.data['name'],
				crname: record.data.cronkid,
				closable: true,
				params: Ext.apply({}, record.data['ae:parameter'], { module: record.data.module, action: record.data.action })
			});
			
			tabPanel.setActiveTab(panel);
		}
	},
	
	initCronkDragZone : function (v) {
		v.dragZone = new Ext.dd.DragZone(v.getEl(), {
			ddGroup: 'cronk',
			
			getDragData: function(e) {
			var sourceEl = e.getTarget(v.itemSelector, 10);
	
	            if (sourceEl) {
	                d = sourceEl.cloneNode(true);
	                d.id = Ext.id();
	                return v.dragData = {
	                    sourceEl: sourceEl,
	                    repairXY: Ext.fly(sourceEl).getXY(),
	                    ddel: d,
	                    dragData: v.getRecord(sourceEl).data
	                }
	
	            }
			
			},
			
			getRepairXY: function() {
				return this.dragData.repairXY;
			}
		
		});
	},
	
	setActiveItem : function(id) {
		this.getLayout().setActiveItem(id);
	},
	
	applyActiveItem : function() {
		var c = this
		if (!Ext.isEmpty(c.active_tab)) {
			c.getLayout().setActiveItem(c.active_tab);
			return true;
		}
		return false;
	},
	
	getContextmenu : function() {
		
		var idPrefix = this.id + '-context-menu';
		
		if (!Ext.isDefined(this.contextmenu)) {
			var ctxMenu = new Ext.menu.Menu({
				
				setItemData : function(view, index, node) {
					this.ctxView = view;
					this.ctxIndex = index;
					this.ctxNode = node;
				},
				
				getItemRecord : function() {
					return this.ctxView.getStore().getAt(this.ctxIndex);
				},
				
				getItemData : function() {
					var r = this.getItemRecord();
					if (Ext.isDefined(r.data)) {
						return r.data;
					}
				},
				
				id: idPrefix,
				
				items: [{
					id: idPrefix + '-button-edit',
					text: _('Edit'),
					iconCls: 'icinga-icon-pencil',
					handler: function(b, e) {
						var cb = Cronk.util.CronkBuilder.getInstance();
						
						if (Ext.isObject(cb)) {
							cb.show(b.getEl());
							cb.setCronkData(ctxMenu.getItemData());
						}
						else {
							AppKit.notifyMessage(_('Error'), _('CronkBuilder has gone away!'));
						}
					}
				}, {
					id: idPrefix + '-button-delete',
					text: _('Delete'),
					iconCls: 'icinga-icon-bin',
					handler: function(b, e) {
						
					}
				}],
				
				listeners: {
					show: function(ctxm) {
						if (this.getItemData().system == true || this.getItemData().owner == false) {
							this.items.get(idPrefix + '-button-edit').setDisabled(true);
							this.items.get(idPrefix + '-button-delete').setDisabled(true);
						}
						else {
							this.items.get(idPrefix + '-button-edit').setDisabled(false);
							this.items.get(idPrefix + '-button-delete').setDisabled(false);
						}
						
						this.items.get(idPrefix + '-button-delete').setDisabled(true);
					}
				}
			});
			
			this.contextmenu = ctxMenu;
		}
		
		return this.contextmenu;
	},
	
	handleContextmenu : function(view, index, node, e) {
		e.stopEvent();
		
		var ctxMenu = this.getContextmenu();
		
		ctxMenu.setItemData(view, index, node);
		
		ctxMenu.showAt(e.getXY());
	},
	
	reloadAll : function() {
		this.removeAll();
		
		Ext.iterate(this.stores, function(storeid, store) {
			store.destroy();
			delete(this.stores[storeid]);
		}, this);
		
		this.loadData(this.combinedProviderUrl);
	}
});
 