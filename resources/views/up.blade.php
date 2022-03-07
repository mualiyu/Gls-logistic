<form action="{{route('dis')}}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="input-group">
                                    <input name="file" class="form-control" required onchange="Upload()" accept=".csv" id="fileUpload" type="file" placeholder="choose file" />
                                    
                                </div>
                                <div class="input-group">
                                </div>
                                <div class="input-group">
                                      <input name="submit" class="btn btn-success" id="submit" type="submit" aria-describedby="nameHelp" value="Upload" />
                                </div>
                            </form>
