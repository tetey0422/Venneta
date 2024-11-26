create database if not exists BDVenneta;
Use BDVenneta;

create table if not exists TUsuario(
	nUsuarioID int auto_increment,
    cEmail varchar(50) unique not null,
    cNombre_Usuario varchar(50) unique not null,
    cContraseña varchar(200) not null,
    eRol enum('user','admin') default 'user' not null,
    primary key(nUsuarioID)
)engine=INNODB;

create table if not exists TCliente(
	nClienteID int auto_increment,
    cNombre varchar(50) not null,
    cApellido Varchar(50) not null,
    cDocumento varchar(20) not null,
    cEmail varchar(50) not null,
    cTelefono varchar(20) not null,
    nUsuarioID int not null,
    primary key(nClienteID),
    foreign key (nUsuarioID) references TUsuario(nUsuarioID)
)engine=INNODB;

create table if not exists TDireccion(
	nDireccionID int auto_increment,
    cDireccion varchar(50) not null,
    nClienteID int not null,
    primary key(nDireccionID),
    foreign key (nClienteID) references TCliente(nClienteID)
)engine=INNODB;
    
create table if not exists TCategoria(
	nCategoriaID int auto_increment,
    cNombre varchar(20) not null,
    cDescripcion varchar(100) not null,
    primary key(nCategoriaID)
)engine=INNODB;

create table if not exists TTalla(
	nTallaID int auto_increment,
    cTalla varchar(20) not null,
    primary key(nTallaID)
)engine=INNODB;

create table TColor(
	nColorID int auto_increment,
    cColor varchar(20) not null,
    primary key(nColorID)
    )engine=InnoDB;
    
create table if not exists TProducto(
	nProductoID int auto_increment,
	cNombre varchar(20) not null,
	cDescripcion varchar(100) not null,
	nPrecio int not null,
	nStock int,
	cImagen varchar(255),
	nCategoriaID int,
    primary key(nProductoID),
    foreign key (nCategoriaID) references TCategoria(nCategoriaID)
)engine=INNODB;

create table if not exists TColor_Producto(
	nColorID int not null,
	nProductoID int not null,
    cImagen varchar(255),
    primary key(nColorID,nProductoID),
    foreign key (nColorID) references TColor(nColorID),
    foreign key (nProductoID) references TProducto(nProductoID)
)engine=INNODB;

create table if not exists TTalla_Producto(
	nTallaID int not null,
	nProductoID int not null,
    nCantidad int not null,
    primary key(nTallaID,nProductoID),
    foreign key (nTallaID) references TTalla(nTallaID),
    foreign key (nProductoID) references TProducto(nProductoID)
)engine=INNODB;

create table if not exists TTalla_Color_Producto (
    nProductoID int not null,
    nTallaID int not null,
    nColorID int not null,
    nCantidad int not null,
    cImagen VARCHAR(255),
    primary key(nProductoID, nTallaID, nColorID),
    foreign key (nProductoID) references TProducto(nProductoID),
    foreign key (nTallaID) references TTalla(nTallaID),
    foreign key (nColorID) references TColor(nColorID)
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

DELIMITER $$

create trigger trg_update_stock
after insert on TTalla_Color_Producto
for each row
begin
    declare total_stock int;

    -- Sumar la cantidad total de todas las combinaciones de tallas y colores para el producto afectado
    select ifnull(sum(nCantidad), 0) into total_stock
    from TTalla_Color_Producto
    where nProductoID = new.nProductoID;

    -- Actualizar el stock total en TProducto
    update TProducto
    set nStock = total_stock
    where nProductoID = new.nProductoID;
end$$

create trigger trg_update_stock_update
after update on TTalla_Color_Producto
for each row
begin
    declare total_stock int;

    -- Sumar la cantidad total de todas las combinaciones de tallas y colores para el producto afectado
    select ifnull(sum(nCantidad), 0) into total_stock
    from TTalla_Color_Producto
    where nProductoID = new.nProductoID;

    -- Actualizar el stock total en TProducto
    update TProducto
    set nStock = total_stock
    where nProductoID = new.nProductoID;
end$$

create trigger trg_update_stock_delete
after delete on TTalla_Color_Producto
for each row
begin
    declare total_stock int;

    -- Sumar la cantidad total de todas las combinaciones de tallas y colores para el producto afectado
    select ifnull(sum(nCantidad), 0) into total_stock
    from TTalla_Color_Producto
    where nProductoID = old.nProductoID;

    -- Actualizar el stock total en TProducto
    update TProducto
    set nStock = total_stock
    where nProductoID = old.nProductoID;
end$$

DELIMITER ;

insert into ttalla(cTalla)
value('S'),('M'),('L'),('XL');

insert into tcolor(cColor)
value('Blanco'),('Negro');

INSERT INTO TCategoria (cNombre, cDescripcion)
VALUES ('Camisetas', 'Camisas de diseño exclusivo');

insert into tproducto(cNombre,cDescripcion,nPrecio, nStock, cImagen, nCategoriaID)
value
('Van Gogh', 'Camisa 100% algodón, cómoda y fresca, con diseño inspirado en Van Gogh', 35000, 50, 'img/vgb.jpg', 1),
('Capitán América', 'Camisa 100% algodón, cómoda y fresca, con diseño del Capitán América', 35000, 50, 'img/capb.jpg', 1),
('Flash', 'Camisa 100% algodón, cómoda y fresca, con diseño de Flash', 35000, 50, 'img/mm.jpg', 1),
('Betty la Fea GTA','Camisa 100% algodón, comoda y fresca, con impresión de Betty la Fea GTA',35000, 50, 'img/bfGTAn.png', 1);

INSERT INTO TTalla_Color_Producto (nProductoID, nTallaID, nColorID, nCantidad, cImagen)
VALUES 
-- Van Gogh
(1, 1, 1, 2, 'img/vgb.jpg'), -- 2 unidades en talla S y color Blanco
(1, 1, 2, 1, 'img/vgn.jpg'), -- 1 unidad en talla S y color Negro
(1, 2, 1, 1, 'img/vgb.jpg'), -- 1 unidad en talla M y color Blanco
(1, 2, 2, 2, 'img/vgn.jpg'), -- 2 unidades en talla M y color Negro
(1, 3, 1, 2, 'img/vgb.jpg'), -- 2 unidades en talla L y color Blanco
(1, 3, 2, 1, 'img/vgn.jpg'), -- 1 unidad en talla L y color Negro
(1, 4, 1, 1, 'img/vgb.jpg'), -- 1 unidad en talla XL y color Blanco
(1, 4, 2, 2, 'img/vgn.jpg'), -- 2 unidades en talla XL y color Negro
-- Capitana América
(2, 1, 1, 2, 'img/capb.jpg'), -- 2 unidades en talla S y color Blanco
(2, 1, 2, 1, 'img/capn.jpg'), -- 1 unidad en talla S y color Negro
(2, 2, 1, 1, 'img/capb.jpg'), -- 1 unidad en talla M y color Blanco
(2, 2, 2, 2, 'img/capn.jpg'), -- 2 unidades en talla M y color Negro
(2, 3, 1, 2, 'img/capb.jpg'), -- 2 unidades en talla L y color Blanco
(2, 3, 2, 1, 'img/capn.jpg'), -- 1 unidad en talla L y color Negro
(2, 4, 1, 1, 'img/capb.jpg'), -- 1 unidad en talla XL y color Blanco
(2, 4, 2, 2, 'img/capn.jpg'), -- 2 unidades en talla XL y color Negro
-- Flash
(3, 1, 1, 2, 'img/mm.jpg'), -- 2 unidades en talla S y color Blanco
(3, 1, 2, 1, 'img/nn.jpg'), -- 1 unidad en talla S y color Negro
(3, 2, 1, 1, 'img/mm.jpg'), -- 1 unidad en talla M y color Blanco
(3, 2, 2, 2, 'img/nn.jpg'), -- 2 unidades en talla M y color Negro
(3, 3, 1, 2, 'img/mm.jpg'), -- 2 unidades en talla L y color Blanco
(3, 3, 2, 1, 'img/nn.jpg'), -- 1 unidad en talla L y color Negro
(3, 4, 1, 1, 'img/mm.jpg'), -- 1 unidad en talla XL y color Blanco
(3, 4, 2, 2, 'img/nn.jpg'), -- 2 unidades en talla XL y color Negro
-- Betty la Fea
(4, 1, 1, 2, 'img/bfGTAb.png'), -- 2 unidades en talla S y color Blanco
(4, 1, 2, 1, 'img/bfGTAn.png'), -- 1 unidad en talla S y color Negro
(4, 2, 1, 1, 'img/bfGTAb.png'), -- 1 unidad en talla M y color Blanco
(4, 2, 2, 2, 'img/bfGTAn.png'), -- 2 unidades en talla M y color Negro
(4, 3, 1, 2, 'img/bfGTAb.png'), -- 2 unidades en talla L y color Blanco
(4, 3, 2, 1, 'img/bfGTAn.png'), -- 1 unidad en talla L y color Negro
(4, 4, 1, 1, 'img/bfGTAb.png'), -- 1 unidad en talla XL y color Blanco
(4, 4, 2, 2, 'img/bfGTAn.png'); -- 2 unidades en talla XL y color Negro