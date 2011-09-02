SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL';


-- -----------------------------------------------------
-- Table `estados`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `estados` ;

CREATE  TABLE IF NOT EXISTS `estados` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `nome` VARCHAR(45) NOT NULL COMMENT 'nome do estado por extenso' ,
  `uf` VARCHAR(2) NOT NULL COMMENT 'sigla do estado' ,
  `criado` DATETIME NOT NULL ,
  `modificado` DATETIME NOT NULL ,
  PRIMARY KEY (`id`) ,
  UNIQUE INDEX `i_nome` (`nome` ASC) ,
  UNIQUE INDEX `i_sigla` (`uf` ASC) ,
  INDEX `i_modified` (`modificado` ASC) )
ENGINE = MyISAM
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;


-- -----------------------------------------------------
-- Table `cidades`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `cidades` ;

CREATE  TABLE IF NOT EXISTS `cidades` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `nome` VARCHAR(45) NOT NULL COMMENT 'nome da cidade' ,
  `criado` DATETIME NOT NULL ,
  `modificado` DATETIME NOT NULL ,
  `estado_id` INT NOT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `i_nome` (`nome` ASC) ,
  INDEX `i_modified` (`modificado` ASC) ,
  INDEX `fk_cidades_estados1` (`estado_id` ASC) ,
  CONSTRAINT `fk_cidades_estados1`
    FOREIGN KEY (`estado_id` )
    REFERENCES `estados` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = MyISAM
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci, 
COMMENT = 'Tabela que contém todas as cidades do brasil' ;


-- -----------------------------------------------------
-- Table `clientes`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `clientes` ;

CREATE  TABLE IF NOT EXISTS `clientes` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `aniversario` VARCHAR(4) NOT NULL ,
  `nome` VARCHAR(60) NOT NULL ,
  `endereco` VARCHAR(60) NULL ,
  `bairro` VARCHAR(45) NOT NULL ,
  `cep` VARCHAR(8) NOT NULL ,
  `telefone` VARCHAR(13) NOT NULL ,
  `celular` VARCHAR(13) NOT NULL ,
  `email` VARCHAR(90) NOT NULL ,
  `obs` TEXT NOT NULL ,
  `criado` DATETIME NOT NULL ,
  `modificado` DATETIME NOT NULL ,
  `cidade_id` INT NOT NULL DEFAULT 2302 ,
  `estado_id` INT NOT NULL DEFAULT 1 ,
  `cpf` INT(11) NOT NULL DEFAULT 0 ,
  `cnpj` INT(14) NOT NULL DEFAULT 0 ,
  PRIMARY KEY (`id`) ,
  INDEX `i_nome` (`nome` ASC) ,
  INDEX `i_email` (`email` ASC) ,
  INDEX `i_modified` (`modificado` ASC) ,
  INDEX `i_endereco` (`endereco` ASC) ,
  INDEX `i_tel` (`celular` ASC, `telefone` ASC) ,
  INDEX `fk_clientes_cidades1` (`cidade_id` ASC) ,
  INDEX `fk_clientes_estados1` (`estado_id` ASC) ,
  INDEX `i_cpf` (`cpf` ASC) ,
  INDEX `i_cnpj` (`cnpj` ASC) ,
  CONSTRAINT `fk_clientes_cidades1`
    FOREIGN KEY (`cidade_id` )
    REFERENCES `cidades` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_clientes_estados1`
    FOREIGN KEY (`estado_id` )
    REFERENCES `estados` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = MyISAM
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci, 
COMMENT = 'tabela de clientes' ;


-- -----------------------------------------------------
-- Table `perfis`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `perfis` ;

CREATE  TABLE IF NOT EXISTS `perfis` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `nome` VARCHAR(45) NOT NULL ,
  `criado` DATETIME NOT NULL ,
  `modificado` DATETIME NOT NULL ,
  PRIMARY KEY (`id`) ,
  UNIQUE INDEX `i_nome` (`nome` ASC) )
ENGINE = MyISAM
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci, 
COMMENT = 'perfis de usuários' ;


-- -----------------------------------------------------
-- Table `usuarios`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `usuarios` ;

CREATE  TABLE IF NOT EXISTS `usuarios` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `login` VARCHAR(45) NOT NULL ,
  `senha` VARCHAR(45) NOT NULL ,
  `criado` DATETIME NOT NULL ,
  `modificado` DATETIME NOT NULL ,
  `ativo` TINYINT(1)  NOT NULL DEFAULT true ,
  `nome` VARCHAR(60) NOT NULL ,
  `email` VARCHAR(45) NOT NULL ,
  `celular` VARCHAR(13) NOT NULL ,
  `ultimo_acesso` DATETIME NOT NULL ,
  `acessos` INT NOT NULL DEFAULT 0 ,
  `trocar_senha` TINYINT(1)  NOT NULL DEFAULT false ,
  PRIMARY KEY (`id`) ,
  UNIQUE INDEX `i_login` (`login` ASC) ,
  INDEX `i_ativo` (`ativo` ASC) ,
  INDEX `i_nome` (`nome` ASC) ,
  INDEX `i_email` (`email` ASC) ,
  INDEX `i_ultimo_acesso` (`ultimo_acesso` ASC) ,
  INDEX `i_acessos` (`acessos` ASC) )
ENGINE = MyISAM;


-- -----------------------------------------------------
-- Table `usuarios_perfis`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `usuarios_perfis` ;

CREATE  TABLE IF NOT EXISTS `usuarios_perfis` (
  `usuario_id` INT NOT NULL ,
  `perfil_id` INT NOT NULL ,
  PRIMARY KEY (`usuario_id`, `perfil_id`) ,
  INDEX `i_usuarios_perfis_perfil` (`perfil_id` ASC) ,
  INDEX `i_usuarios_perfis_usuario` (`usuario_id` ASC) ,
  CONSTRAINT `fk_usuarios_has_perfis_usuarios1`
    FOREIGN KEY (`usuario_id` )
    REFERENCES `usuarios` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_usuarios_has_perfis_perfis1`
    FOREIGN KEY (`perfil_id` )
    REFERENCES `perfis` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = MyISAM;


-- -----------------------------------------------------
-- Table `permissoes`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `permissoes` ;

CREATE  TABLE IF NOT EXISTS `permissoes` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `controlador` VARCHAR(50) NOT NULL ,
  `acao` VARCHAR(99) NOT NULL ,
  `acesso` TINYINT(1)  NOT NULL DEFAULT true ,
  `criado` DATETIME NOT NULL ,
  `modificado` DATETIME NOT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `i_controlador` (`controlador` ASC) ,
  INDEX `i_acao` (`acao` ASC) )
ENGINE = MyISAM
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci, 
COMMENT = 'Cadastro de permissões' ;


-- -----------------------------------------------------
-- Table `permissoes_perfis`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `permissoes_perfis` ;

CREATE  TABLE IF NOT EXISTS `permissoes_perfis` (
  `permissao_id` INT NOT NULL ,
  `perfil_id` INT NOT NULL ,
  PRIMARY KEY (`permissao_id`, `perfil_id`) ,
  INDEX `fk_permissoes_perfis_perfis1` (`perfil_id` ASC) ,
  INDEX `fk_permissoes_perfis_permissoes1` (`permissao_id` ASC) ,
  CONSTRAINT `fk_permissoes_has_perfis_permissoes1`
    FOREIGN KEY (`permissao_id` )
    REFERENCES `permissoes` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_permissoes_has_perfis_perfis1`
    FOREIGN KEY (`perfil_id` )
    REFERENCES `perfis` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = MyISAM
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;


-- -----------------------------------------------------
-- Table `usuarios_permissoes`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `usuarios_permissoes` ;

CREATE  TABLE IF NOT EXISTS `usuarios_permissoes` (
  `usuario_id` INT NOT NULL ,
  `permissao_id` INT NOT NULL ,
  PRIMARY KEY (`usuario_id`, `permissao_id`) ,
  INDEX `fk_usuarios_permissoes_permissoes1` (`permissao_id` ASC) ,
  INDEX `fk_usuarios_permissoes_usuarios1` (`usuario_id` ASC) ,
  CONSTRAINT `fk_usuarios_has_permissoes_usuarios1`
    FOREIGN KEY (`usuario_id` )
    REFERENCES `usuarios` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_usuarios_has_permissoes_permissoes1`
    FOREIGN KEY (`permissao_id` )
    REFERENCES `permissoes` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = MyISAM;



SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
