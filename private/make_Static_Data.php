<?php
    // Not working with mysqli_query()
    // (I couldn't find out why)
    
    $cnf = parse_ini_file("admin_config.sh"); // INI format similar to SH
    $servername = $cnf["SERVER"];
    $username = $cnf["USER"];
    $password = $cnf["PASSWORD"];
    $dbname = $cnf["DATABASE"];
    
    try {
        $conn = new PDO("mysql:host=$servername;port=3306;dbname=$dbname", $username, $password);
        // set the PDO error mode to exception
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        //echo "Connected successfully";
        // SQL Query
        // columns with spacings: https://www.tutorialspoint.com/how-to-select-a-column-name-with-spaces-in-mysql
        // utf8_decode() to handle tildes and ñ in columns' names
        
        $conn->exec("DROP TABLE IF EXISTS Static_Data; CREATE TABLE Static_Data (id INTEGER PRIMARY KEY NOT NULL AUTO_INCREMENT, `Departamento` VARCHAR(75), `Núcleo_Básico` VARCHAR(100), `Especialización` VARCHAR(100), `Otros` VARCHAR(100)) DEFAULT CHARACTER SET utf8 COLLATE utf8_spanish_ci");
        
        $conn->exec("INSERT INTO Static_Data (`Departamento`) VALUES ('Amazonas'), ('Antioquia'), ('Arauca'), ('Archipiélago de San Andrés, Providencia y Santa Catalina'), ('Atlántico'), ('Bogotá D.C.'), ('Bolívar'), ('Boyacá'), ('Caldas'), ('Caquetá'), ('Casanare'), ('Cauca'), ('Cesar'), ('Chocó'), ('Córdoba'), ('Cundinamarca'), ('Guainía'), ('Guaviare'), ('Huila'), ('La Guajira'), ('Magdalena'), ('Meta'), ('Nariño'), ('Norte de Santander'), ('Putumayo'), ('Quindío'), ('Risaralda'), ('Santander'), ('Sucre'), ('Tolima'), ('Valle del Cauca'), ('Vaupés'), ('Vichada')");
        
        // WARNING: Order is important, for example: 'Ingeniería' debe preceder a
        // 'Ingeniería Industrial' y así.  Crucial para update_Jobs..._Data.php
        $conn->exec("INSERT INTO Static_Data (`Núcleo_Básico`) VALUES ('Administración'), ('Administrativo'), ('Administrativa'), ('Agronómica'), ('Agronomía'), ('Alimentos'), ('Archivística'), ('Arquitectura'), ('Antropología'), ('Bacteriología'), ('Bibliotecología'), ('Biología'), ('Civil'), ('Constitucional'), ('Contaduría'), ('Derecho'), ('Deportes'), ('Diseño'), ('Economía'), ('Educación'), ('Eléctrica'), ('Electrónica'), ('Enfermería'), ('Estadística'), ('Familia'), ('Filosofía'), ('Forestal'), ('Geografía'), ('Historia'), ('Ingeniería'), ('Informática'), ('Industrial'), ('Justicia'), ('Leyes'), ('Medicina'), ('Mecánica'), ('Literatura'), ('Lingüística'), ('Lingüista'), ('Matemáticas'), ('Matemática'), ('Metalurgia'), ('Microbiología'), ('Fisioterapia'), ('Odontología'), ('Periodismo'), ('Periodista'), ('Pecuaria'), ('Publicidad'), ('Publicista'), ('Procesal'), ('Pública'), ('Psicología'), ('Politólogo'), ('Química'), ('Sanitaria'), ('Sistemas'), ('Sociología'), ('Telemática'), ('Telecomunicaciones'), ('Teología'), ('Urbanismo'), ('Urbanista'), ('Veterinaria'), ('Zootecnia'), ('Ingeniería Civil'), ('Ingeniería Industrial'), ('Ingeniería Mecánica'), ('Ingeniería de Sistemas'), ('Ingeniería Eléctrica'), ('Ingeniería Electrónica'), ('Ingeniería Informática'), ('Ingeniería de Sistemas e Informática'), ('Ingeniería de Petróleos'), ('Ingeniería de Minas'), ('Ingeniería Química'), ('Ingeniería Agronómica'), ('Ingeniería Agrícola'), ('Ingeniería Agroindustrial'), ('Ingeniería Forestal'), ('Ingeniería de Alimentos'), ('Ingeniería de Calidad'), ('Ingeniería en Calidad'), ('Ingeniería Ambiental'), ('Ingeniería Sanitaria'), ('Ingeniería del Desarrollo Ambiental'), ('Ingeniería Administrativa'), ('Ingeniería Financiera'), ('Ingeniería Financiera y de Negocios'), ('Ingeniería Administrativa y de Finanzas'), ('Ingeniería en Seguridad Industrial'), ('Ingeniería Catastral y Geodesia'),('Higiene Ocupacional'), ('Bibliotecología y Archivología'), ('Ciencias de la Información y de la Documentación'), ('Ciencias de la Información y la Documentación'), ('Ciencias Agronómicas'), ('Ciencia Agronómica'), ('Ciencias Administrativas'), ('Ciencias Sociales'), ('Ciencias Humanas'), ('Ciencias Sociales y Humanas'), ('Ciencia Política'), ('Ciencia de la Información'), ('Ciencias Políticas'), ('Sistemas de Información'), ('Salud Pública'), ('Salud Ocupacional'), ('Medicina Veterinaria'), ('Economía y Finanzas'), ('Economía y Desarrollo'), ('Laboratorio Clínico'), ('Trabajo Social'), ('Comunicación Social'), ('Artes Liberales'), ('Justicia y Derecho'), ('Bibliotecología y Archivística'), ('Leyes y Jurisprudencia'), ('Física y Recreación'), ('Educación Física y Recreación'), ('Lenguas Modernas')");
        $conn->exec("INSERT INTO Static_Data (`Especialización`) VALUES ('Contaduría Pública'), ('Contaduría Internacional'), ('Relaciones Internacionales'), ('Derecho Laboral'), ('Derecho Laboral y Seguridad Social'), ('Derecho del Trabajo'), ('Derecho del Trabajo y Seguridad Social'), ('Derecho Procesal'), ('Derechos Humanos'), ('Derecho Constitucional'), ('Derecho Administrativo'), ('Derecho Civil'), ('Derecho de Familia'), ('Jurisprudencia'), ('Comercio Internacional'), ('Administración Pública'), ('Administración de Empresas'), ('Administración de Empresas o Pública'), ('Administración Financiera'), ('Financiera'), ('Finanzas'), ('Administración Ambiental'), ('Administración Ambiental y de los Recursos Naturales'), ('Psicología Familiar')");
        $conn->exec("INSERT INTO Static_Data (`Otros`) VALUES ('Bachiller'), ('Terapias'), ('Justicia y Derecho'), ('Defensor de Familia')");
        
        return true;
    } catch(PDOException $e) {
        echo "Error: " . "<br>" . $e->getMessage();
    }
    $conn = null;
?>
