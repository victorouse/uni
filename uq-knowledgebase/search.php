                    <div class="navbar navbar-default" style="text-align:center">
                        <form class="navbar-form" action="results.php" method="POST">
                            <div class="form-group">
                                <input type="search" class="form-control" placeholder="Enter search term" name="term" style="width:300px">
                            </div>
                            <div id="searchfilter" class="btn-group form-group" data-toggle="buttons">
                                <label class="btn btn-primary rdo active">
                                    <input type="radio" name="options" id="option1" value="title" checked>Title 
                                </label>
                                <label class="btn btn-primary rdo">
                                    <input type="radio" name="options" id="option2" value="tag">Tag 
                                </label>
                                <label class="btn btn-primary rdo">
                                    <input type="radio" name="options" id="option3" value="author">Author 
                                </label>
                            </div>
                            <button type="submit" class="btn btn-default" name="search">Search</button>
                        </form>
                    </div>
