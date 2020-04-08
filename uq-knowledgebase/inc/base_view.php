<?php
    class ViewModel {
        public $model;
        public $user = 4;

        public function __construct() {
            $this->model = new DataModel();
        }

        public function displayTags($issue) {
            $htmlTags = "<div class='tags'>";

            $tags = explode(',', trim($issue['tags']));
            foreach ($tags as $tag) {
                $tagSpan = "<span class='label label-primary tag'>%1\$s</span>";
                $htmlTags .= sprintf($tagSpan, $tag);
            }

            $htmlTags .= "</div>";
            return $htmlTags;
        }

        public function createPanel($heading, $body, $style, $footer=NULL) {
            $html = "";
            
            $panel = 
            "
                <div class='panel %1\$s'>
            ";
            $panel = sprintf($panel, $style);
            $html .= $panel;

            $panelHeading = 
            "
                    <div class='panel-heading'>
                        %1\$s
                    </div>
            ";
            $panelHeading = sprintf($panelHeading, $heading);
            $html .= $panelHeading;

            $panelBody = 
            "
                    <div class='panel-body'>
                        %1\$s
                    </div>
            ";
            $panelBody = sprintf($panelBody, $body);
            $html .= $panelBody;

            if ($footer) {
                $panelFooter =
                "
                        <div class='panel-footer'>
                            %1\$s
                        </div>
                ";
                $panelFooter = sprintf($panelFooter, $footer);
                $html .= $panelFooter;
            }

            $html .= "</div>";
            return $html;
        }

        public function createCollapsiblePanel($id, $style, $heading, $body, $footer=NULL) {
            $html = "";
            
            $panel = 
            "
                <div class='panel %1\$s panel-pad'>
            ";
            $panel = sprintf($panel, $style);
            $html .= $panel;

            $panelHeading = 
            "
                    <div class='panel-heading'>
                        <h3 class='panel-title post-title'>
                            <a data-toggle='collapse' data-target='#%1\$s'> 
                                %2\$s
                            </a>
                        </h3>
                    </div>
            ";
            $panelHeading = sprintf($panelHeading, $id, $heading);
            $html .= $panelHeading;

            $panelBody = 
            "
                    <div id='%1\$s' class='topic-panel panel-collapse collapse in'>
                        <div class='panel-body panel-nopad'>
                            %2\$s
                        </div>
            ";
            $panelBody = sprintf($panelBody, $id, $body);
            $html .= $panelBody;

            if ($footer) {
                $panelFooter =
                "
                        <div class='panel-footer'>
                            %1\$s
                        </div>
                ";
                $panelFooter = sprintf($panelFooter, $footer);
                $html .= $panelFooter;
            }

            // close collapse area + panel div 
            $html .= "</div></div>";
            return $html;
        }
    } 
?>
