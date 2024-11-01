create database if not exists BDVenneta;
Use BDVenneta;

create table if not exists TUsuario(
	nUsuarioID int auto_increment,
    cEmail varchar(50) unique,
    cNombre_Usuario varchar(50),
    cContraseña varchar(200),
    primary key(nUsuarioID)
)engine=INNODB;

create table if not exists TCliente(
	nClienteID int auto_increment,
    cNombre varchar(50),
    cApellido Varchar(50),
    cDocumento varchar(20),
    cEmail varchar(50),
    cTelefono varchar(20),
    nUsuarioID int,
    primary key(nClienteID),
    foreign key (nUsuarioID) references TUsuario(nUsuarioID)
)engine=INNODB;

create table if not exists TDireccion(
	nDireccionID int auto_increment,
    cDireccion varchar(50),
    nClienteID int,
    primary key(nDireccionID),
    foreign key (nClienteID) references TCliente(nClienteID)
)engine=INNODB;
    
create table if not exists TCategoria(
	nCategoriaID int auto_increment,
    cNombre varchar(20),
    cDescripcion varchar(100),
    primary key(nCategoriaID)
)engine=INNODB;

create table if not exists TTalla(
	nTallaID int auto_increment,
    cTalla varchar(20),
    primary key(nTallaID)
)engine=INNODB;

create table TColor(
	nColorID int auto_increment,
    cColor varchar(20),
    primary key(nColorID)
    )engine=InnoDB;
    
create table if not exists TProducto(
	nProductoID int auto_increment,
	cNombre varchar(20),
	cDescripcion varchar(100),
	nPrecio int,
	nStock int,
	cImagen varchar(255),
	nCategoriaID int,
    primary key(nProductoID),
    foreign key (nCategoriaID) references TCategoria(nCategoriaID)
)engine=INNODB;

create table if not exists TColor_Producto(
	nColorID int not null,
	nProductoID int not null,
    primary key(nColorID,nProductoID),
    foreign key (nColorID) references TColor(nColorID),
    foreign key (nProductoID) references TProducto(nProductoID)
)engine=INNODB;

create table if not exists TTalla_Producto(
	nTallaID int not null,
	nProductoID int not null,
    primary key(nTallaID,nProductoID),
    foreign key (nTallaID) references TTalla(nTallaID),
    foreign key (nProductoID) references TProducto(nProductoID)
)engine=INNODB;

create table if not exists TPedido(
	nPedidoID int auto_increment,
	dFecha date,
	nSubTotal decimal(10, 2),
	cEstado varchar(20),
    nCantidad int,
	nPrecioUnitario decimal(10, 2),
    nProductoID int,
    primary key(nPedidoID),
    foreign key (nProductoID) references TProducto(nProductoID)
)engine=INNODB;

create table if not exists TEmpresaEnvio(
	nEmpresaEnvioID int auto_increment,
    cEmpresa varchar (20),
    cTelefono varchar (20),
    primary key(nEmpresaEnvioID)
)engine=INNODB;

create table if not exists TEnvio(
	nEnvioID int auto_increment,
	cNombre varchar(50),
	cApellido varchar(50),
	cDocumento varchar(50),
	cTelefono varchar(20),
	nDireccionID int,
	nCosto float,
	nPedidoID int,
    nGuia varchar (50),
    nEmpresaEnvioID int,
    primary key(nEnvioID),
    foreign key(nPedidoID) references TPedido(nPedidoID),
    foreign key(nDireccionID) references TDireccion(nDireccionID),
    foreign key(nEmpresaEnvioID) references TEmpresaEnvio(nEmpresaEnvioID)
)engine=INNODB;

create table if not exists TMetodoPago(
	nMetodoPagoID int auto_increment,
	nTipo varchar(20),
    primary key(nMetodoPagoID)
)engine=INNODB;

create table if not exists TEmpleadoLogin(
	nEmpleadoLoginID int auto_increment,
	cUsuario varchar(20),
    cContraseña varchar(200),
    primary key(nEmpleadoLoginID)
)engine=INNODB;

create table if not exists THistorial(
	nHistorialID int auto_increment,
	dIngreso date,
    nEmpleadoLoginID int,
    primary key(nHistorialID),
    foreign key (nEmpleadoLoginID) references TEmpleadoLogin(nEmpleadoLoginID)
)engine=INNODB;

create table if not exists TEmpleado(
	nEmpleadoID int auto_increment,
	cNombre varchar(20),
    cApellido varchar(200),
    cTelefono varchar (20),
    cCargo varchar (20),
    nEmpleadoLoginID int,
    primary key(nEmpleadoID),
    foreign key (nEmpleadoLoginID) references TEmpleadoLogin(nEmpleadoLoginID)
)engine=INNODB;

create table if not exists TFactura(
	nFacturaID int auto_increment,
	dFactura date,
    nClienteID int,
	nIVA float,
	nTotal float,
	nPedidoID int,
	nMetodoPagoID int,
    nEmpleadoLoginID int,
    primary key(nFacturaID),
	foreign key(nPedidoID) references TPedido(nPedidoID),
    foreign key(nMetodoPagoID) references TMetodoPago(nMetodoPagoID),
    foreign key(nClienteID) references TCliente(nClienteID),
    foreign key (nEmpleadoLoginID) references TEmpleadoLogin(nEmpleadoLoginID)
)engine=INNODB;

insert into tproducto(cNombre,cDescripcion,nPrecio,cImagen)
value('Betty la Fea GTA','Camisa 100% algodón, comoda y fresca, con impresión de Betty la Fea GTA',35000,'img/bfGTAn.png'),
('Van Gogh', 'Camisa 100% algodón, cómoda y fresca, con diseño inspirado en Van Gogh', 35000, 'img/vgb.jpg'),
('Capitán América', 'Camisa 100% algodón, cómoda y fresca, con diseño del Capitán América', 35000, 'img/capn.jpg'),
('Flash', 'Camisa 100% algodón, cómoda y fresca, con diseño de Flash', 35000, 'img/mm.jpg');

insert into ttalla(cTalla)
value('S'),('M'),('L'),('XL'),('XXL');

insert into tcolor(cColor)
value('Blanco'),('Negro');