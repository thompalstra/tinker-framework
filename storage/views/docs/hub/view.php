<?php
use Hub\Base\View;
?>
<style>
    a:focus + .card{
        background: #f8f9fa;
    }
    a:focus + .card .card-title{
        color: #17a2b8;
    }
    .card .code.description{
        white-space: pre-line;
        /* background: #000; */
        /* color: #DD8; */
        /* padding: 20px; */
    }
</style>
<div class="container">
    <div class="row">
        <div class="col-12 col-md-3 col-lg-3 my-4">
            <ul>
                <?php foreach($items as $key => $item) : ?>
                    <?php
                    $reflect = new \ReflectionClass($item);
                    ?>
                    <li>
                        <a href='#<?=$reflect->getName()?>'><?=$key?></a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
        <div class="col-12 col-md-9 col-lg-9 mb-4">
            <?php foreach($items as $key => $item) : ?>
                <?php
                $reflect = new \ReflectionClass($item);
                $parentClass = $reflect->getParentClass();
                $interfaces = $reflect->getInterfaceNames();

                $interface = null;

                if(count($interfaces) > 0){
                    $interface = new \ReflectionClass($interfaces[0]);
                }
                ?>

                <a id="<?=$reflect->getName()?>" href=""></a>
                <div class="card mt-4">
                    <div class="card-body">
                        <h5 class="card-title"><?=$key?></h5>
                        <h6 class="card-subtitle mb-2 text-muted">
                            <?php if($item) : ?>
                                class <?=$item?>
                            <?php endif; ?>
                            <?php if($parentClass) : ?>
                                <?php
                                $url = '';
                                $parts = explode("\\", $parentClass->getNamespaceName());
                                $url = strtolower(implode("/", $parts));
                                $url = "/docs/{$url}#{$parentClass->getName()}";
                                ?>
                                extends <a href="<?=$url?>"><?=$parentClass->getName()?></a>
                            <?php endif; ?>
                            <?php if($interface) : ?>
                                <?php
                                $url = '';
                                $parts = explode("\\", $interface->getNamespaceName());
                                $url = strtolower(implode("/", $parts));
                                $url = "/docs/{$url}#{$interface->getName()}";
                                ?>
                                implements <a href="<?=$url?>"><?=$interface->getName()?></a>
                            <?php endif; ?>
                        </h6>

                        <?php foreach(get_class_methods($item) as $method) : ?>
                            <?php
                            $reflectionMethod = new \ReflectionMethod($item, $method);
                            $docComment = $reflectionMethod->getDocComment();

                            $visibility = '';

                            $function = [];
                            $args = [];

                            if($reflectionMethod->isPrivate()){
                                $function[] = 'private';
                            } elseif($reflectionMethod->isProtected()){
                                $function[] = 'protected';
                            } elseif($reflectionMethod->isPublic()){
                                $function[] = 'public';
                            }
                            if($reflectionMethod->isStatic()){
                                $function[] = 'static';
                            }
                            $function[] = 'function';
                            $function[] = $method;
                            $function = implode(' ', $function);

                            foreach($reflectionMethod->getParameters() as $param){
                                $reflectionParameter = new \ReflectionParameter([$item, $method], $param->getName());
                                $arg = [];

                                $type = null;

                                if($reflectionParameter->hasType()){
                                    $type = $reflectionParameter->getType();
                                    $arg[] = $type;
                                }

                                $paramName = $param->getName();
                                $isOptional = $param->isOptional();
                                $paramDefaultValue = $param->isDefaultValueAvailable() ? $param->getDefaultValue() : null;

                                $arg[] = '$' . $param->getName();
                                if($paramDefaultValue){
                                    if($type && $type == 'bool'){
                                        $arg[] = '= <var>' . (($paramDefaultValue) ? 'true' : 'false') . "</var>";
                                    } else {
                                        $arg[] = "= <var>" . strval($paramDefaultValue) . "</var>";
                                    }
                                }
                                $args[] = implode(" ", $arg);
                            }
                            $args = implode(", ", $args);
                            ?>
                            <p class="card-text"><?=$function?>(<?=$args?>)</p>

                            <?php if(strlen($docComment) > 0) : ?>
                                <pre class="code description"><?=$docComment?></pre>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>
