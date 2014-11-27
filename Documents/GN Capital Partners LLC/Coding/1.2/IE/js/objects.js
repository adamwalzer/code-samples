function Menu(category[])
{
	this.category[]= category[];
}

Menu.prototype.addCategory= function(category[], x)
{
	this.category.splice(x, 0, category[]);
}






function Category(name, note, subcategory[])
{
	this.name= name;
	this.note= note;
	this.subcategory[]= subcategory[];
}

Category.prototype.addSubcategory= function(subcategory, x)
{
	this.subcategory.splice(x, 0, subcategory);
}







function Subcategory(name, note, size[], item[])
{
	this.name= name;
	this.note= note;
	this.size[]= size[];
	this.item[]= item[];
}

Subcategory.prototype.addItem= function(item[], x)
{
	this.item.splice(x, 0, item[]);
}

